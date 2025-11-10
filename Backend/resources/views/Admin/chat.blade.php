@extends('layouts.admin')
@section('title', 'Chat AI Assistant')
@section('content')
<div class="relative md:flex h-[calc(100vh-10rem)] bg-white rounded-xl shadow-lg overflow-hidden">

    <div id="conversation-sidebar" class="absolute top-0 left-0 h-full w-full sm:w-80 bg-gray-50 border-r md:rounded-l-xl flex flex-col z-30 transform -translate-x-full transition-transform duration-300 ease-in-out md:static md:w-1/4 md:flex md:translate-x-0">
        <div class="p-4 border-b flex justify-between items-center flex-shrink-0">
            <h2 class="text-xl font-bold text-gray-800">Chat</h2>
            <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold h-9 w-9 rounded-lg flex items-center justify-center shadow-md hover:shadow-lg transition-all" title="New Chat">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="flex-grow overflow-y-auto">
            <nav class="p-2 space-y-1">
                @forelse($conversations as $conv)
                    <div class="flex justify-between items-center p-3 rounded-lg group transition-colors duration-200 {{ optional($activeConversation)->id === $conv->id ? 'bg-blue-100' : 'hover:bg-gray-200' }}">
                        <a href="{{ route('admin.chat', $conv) }}" class="flex-grow flex items-center truncate mr-2">
                            <i class="fa-regular fa-message-dots w-5 mr-3 {{ optional($activeConversation)->id === $conv->id ? 'text-blue-600' : 'text-gray-600' }}"></i>
                            <span class="{{ optional($activeConversation)->id === $conv->id ? 'text-blue-700 font-semibold' : 'text-gray-700' }}">{{ $conv->title }}</span>
                        </a>
                        <form action="{{ route('admin.chat.destroy', $conv) }}" method="POST" class="delete-conversation-form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="flex-shrink-0 text-gray-400 hover:text-red-600 w-8 h-8 rounded-md flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" title="Delete Chat">
                                <i class="fa-solid fa-trash-can"></i>
                            </button>
                        </form>
                    </div>
                @empty
                    <p class="text-center text-gray-500 p-4">No conversations yet.</p>
                @endforelse
            </nav>
        </div>
    </div>

    <div id="sidebar-overlay" class="fixed inset-0 bg-black bg-opacity-50 z-20 md:hidden hidden"></div>

    <div class="w-full md:w-3/4 flex flex-col h-full">
        <div class="p-3 border-b flex justify-between items-center md:hidden flex-shrink-0 bg-white z-10 shadow-sm">
            <button id="sidebar-toggle-btn" class="text-gray-600 hover:text-gray-800 p-2 -ml-2 rounded-full">
                <i class="fa-solid fa-bars fa-lg"></i>
            </button>
            <h2 class="text-lg font-bold text-gray-800 truncate px-2">
                {{ optional($activeConversation)->title ?? 'AI Assistant' }}
            </h2>
            <div class="w-8"></div>
        </div>

        <div id="chat-container" class="flex-grow p-4 sm:p-6 overflow-y-auto space-y-6">
            @if($activeConversation)
                @foreach($activeConversation->messages as $message)
                    <div class="flex items-start gap-3 sm:gap-4 {{ $message->role === 'user' ? 'justify-end' : '' }}">
                        @if($message->role !== 'user')
                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-700 flex items-center justify-center text-white"><i class="fa-solid fa-robot"></i></div>
                        @endif
                        <div class="max-w-[80%] sm:max-w-md md:max-w-lg lg:max-w-2xl px-4 py-2.5 sm:px-5 sm:py-3 rounded-2xl {{ $message->role === 'user' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 text-gray-800 rounded-bl-none' }}">
                            <div class="prose">{!! \Illuminate\Support\Str::markdown($message->content) !!}</div>
                        </div>
                         @if($message->role === 'user')
                            <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-600 flex items-center justify-center text-white"><i class="fa-solid fa-user"></i></div>
                        @endif
                    </div>
                @endforeach
            @else
                <div class="flex items-center justify-center h-full">
                    <div class="text-center text-gray-400">
                        <i class="fa-solid fa-comments fa-5x mb-4"></i>
                        <h3 class="text-2xl font-semibold">AI Assistant</h3>
                        <p class="mt-2">Select a conversation or start a new one.</p>
                    </div>
                </div>
            @endif
        </div>

        <div id="typing-indicator" class="px-4 sm:px-6 pb-2 hidden flex-shrink-0">
            <div class="flex items-start gap-3 sm:gap-4">
                <div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-700 flex items-center justify-center text-white"><i class="fa-solid fa-robot"></i></div>
                <div class="px-5 py-3 rounded-2xl bg-gray-200 text-gray-800 rounded-bl-none">
                    <div class="flex items-center space-x-1">
                        <span class="font-semibold">AI is typing</span>
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-pulse [animation-delay:-0.3s]"></div>
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-pulse [animation-delay:-0.15s]"></div>
                        <div class="w-2 h-2 bg-gray-500 rounded-full animate-pulse"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-3 sm:p-4 bg-white border-t flex-shrink-0">
             @if(session('error'))
                <p class="text-red-500 text-sm mb-2 text-center">{{ session('error') }}</p>
            @endif
            <form id="chat-form" action="{{ route('admin.chat.store') }}" method="POST">
                @csrf
                <input type="hidden" id="conversation_id_field" name="conversation_id" value="{{ optional($activeConversation)->id }}">
                <div class="flex items-center bg-gray-100 rounded-lg p-1.5 border border-gray-200 transition-all duration-200 focus-within:ring-2 focus-within:ring-blue-300">
                    <input type="text" id="prompt-input" name="prompt" placeholder="Ask me anything..." class="flex-grow bg-transparent border-none focus:ring-0 focus:outline-none text-gray-800 py-2 px-2" autocomplete="off" required>
                    <button type="submit" id="send-btn" class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white font-semibold rounded-lg px-4 py-2.5 sm:px-5 ml-2 shadow-sm hover:shadow-md transition-all duration-200">
                        <i class="fa-solid fa-paper-plane"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="newChatModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm m-4 text-center">
        <i class="fa-solid fa-question-circle fa-3x text-blue-500 mb-4"></i>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Start a New Chat?</h2>
        <p class="text-gray-600 mb-6">This will create a new conversation session.</p>
        <div class="flex justify-center space-x-4">
            <button id="cancelBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg">Cancel</button>
            <a href="{{ route('admin.chat') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg">Confirm</a>
        </div>
    </div>
</div>

<div id="deleteConfirmationModal" class="fixed inset-0 bg-black bg-opacity-60 z-50 hidden flex items-center justify-center p-4">
    <div class="bg-white p-8 rounded-2xl shadow-2xl w-full max-w-sm m-4 text-center">
        <i class="fa-solid fa-trash-can fa-3x text-red-500 mb-4"></i>
        <h2 class="text-2xl font-bold text-gray-800 mb-2">Delete Conversation?</h2>
        <p class="text-gray-600 mb-6">Are you sure? This action cannot be undone.</p>
        <div class="flex justify-center space-x-4">
            <button id="cancelDeleteBtn" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-6 rounded-lg">Cancel</button>
            <button id="confirmDeleteBtn" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg">Confirm Delete</button>
        </div>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css">
    <style>
        .prose{max-width:100%}.prose h1,.prose h2,.prose h3{margin-top:1em;margin-bottom:.5em;line-height:1.2}.prose p{margin-bottom:1em}.prose ul,.prose ol{margin-left:1.5em;margin-bottom:1em}.prose li{margin-bottom:.25em}.prose pre{background-color:#282c34;color:#abb2bf;padding:1em;border-radius:.5rem;margin-bottom:1em;white-space:pre-wrap}.prose code{font-family:'Courier New',Courier,monospace}.prose blockquote{border-left:4px solid #ccc;padding-left:1em;margin-left:0;font-style:italic;color:#666}.prose a{color:#3b82f6;text-decoration:underline}
    </style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const chatContainer = document.getElementById('chat-container');
        const chatForm = document.getElementById('chat-form');
        const promptInput = document.getElementById('prompt-input');
        const sendBtn = document.getElementById('send-btn');
        const conversationIdField = document.getElementById('conversation_id_field');
        const typingIndicator = document.getElementById('typing-indicator');

        const sidebar = document.getElementById('conversation-sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        const toggleBtn = document.getElementById('sidebar-toggle-btn');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                openSidebar();
            });
        }
        
        if (overlay) {
            overlay.addEventListener('click', closeSidebar);
        }

        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function appendMessage(role, content, isMarkdown = true) {
            const messageWrapper = document.createElement('div');
            messageWrapper.className = `flex items-start gap-3 sm:gap-4 ${role === 'user' ? 'justify-end' : ''}`;
            
            const avatarHtml = role === 'user'
                ? `<div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-blue-600 flex items-center justify-center text-white"><i class="fa-solid fa-user"></i></div>`
                : `<div class="flex-shrink-0 w-8 h-8 sm:w-10 sm:h-10 rounded-full bg-gray-700 flex items-center justify-center text-white"><i class="fa-solid fa-robot"></i></div>`;

            const messageBubble = document.createElement('div');
            messageBubble.className = `max-w-[80%] sm:max-w-md md:max-w-lg lg:max-w-2xl px-4 py-2.5 sm:px-5 sm:py-3 rounded-2xl ${role === 'user' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 text-gray-800 rounded-bl-none'}`;
            
            const contentContainer = document.createElement('div');
            if (isMarkdown) {
                contentContainer.className = 'prose';
                contentContainer.innerHTML = marked.parse(content);
            } else {
                contentContainer.textContent = content;
            }
            
            messageBubble.appendChild(contentContainer);

            if (role === 'user') {
                messageWrapper.appendChild(messageBubble);
                messageWrapper.innerHTML += avatarHtml;
            } else {
                messageWrapper.innerHTML = avatarHtml;
                messageWrapper.appendChild(messageBubble);
            }
            
            chatContainer.appendChild(messageWrapper);
            if (isMarkdown) hljs.highlightAll();
            scrollToBottom();
        }

        chatForm.addEventListener('submit', function (e) {
            e.preventDefault();
            const prompt = promptInput.value.trim();
            if (!prompt) return;

            appendMessage('user', prompt, false);
            promptInput.value = '';

            sendBtn.disabled = true;
            sendBtn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
            typingIndicator.classList.remove('hidden');
            scrollToBottom();
            
            const formData = new FormData(this);
            formData.set('prompt', prompt);

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                }
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw new Error(err.error || 'Server error'); });
                }
                return response.json();
            })
            .then(data => {
                appendMessage('assistant', data.reply);
                if (!conversationIdField.value && data.conversation_id) {
                    const newUrl = `{{ url('admin/chat') }}/${data.conversation_id}`;
                    window.history.pushState({path: newUrl}, '', newUrl);
                    conversationIdField.value = data.conversation_id;
                    document.querySelector('.md\\:hidden h2').textContent = data.title;
                }
            })
            .catch(error => {
                appendMessage('assistant', `**Error:** ${error.message}`);
            })
            .finally(() => {
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fa-solid fa-paper-plane"></i>';
                typingIndicator.classList.add('hidden');
            });
        });
        
        scrollToBottom();
        document.querySelectorAll('.prose').forEach(el => { el.innerHTML = marked.parse(el.innerHTML); });
        hljs.highlightAll();

        const openModalBtn = document.getElementById('openModalBtn');
        const newChatModal = document.getElementById('newChatModal');
        const cancelBtn = document.getElementById('cancelBtn');

        openModalBtn.addEventListener('click', () => newChatModal.classList.remove('hidden'));
        cancelBtn.addEventListener('click', () => newChatModal.classList.add('hidden'));
        newChatModal.addEventListener('click', (e) => { if (e.target === newChatModal) newChatModal.classList.add('hidden') });
        
        const deleteConfirmationModal = document.getElementById('deleteConfirmationModal');
        const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
        const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
        let formToSubmit = null;

        document.querySelectorAll('.delete-conversation-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                formToSubmit = e.target;
                deleteConfirmationModal.classList.remove('hidden');
            });
        });

        confirmDeleteBtn.addEventListener('click', () => {
            if (formToSubmit) {
                formToSubmit.submit();
            }
        });

        cancelDeleteBtn.addEventListener('click', () => {
            deleteConfirmationModal.classList.add('hidden');
            formToSubmit = null;
        });

        deleteConfirmationModal.addEventListener('click', (e) => {
            if (e.target === deleteConfirmationModal) {
                deleteConfirmationModal.classList.add('hidden');
                formToSubmit = null;
            }
        });
    });
</script>
@endpush
@endsection