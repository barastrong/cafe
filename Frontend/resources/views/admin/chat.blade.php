@extends('layouts.admin')
@section('title', 'Chat AI Assistant')
@section('content')
<div class="flex h-[calc(100vh-10rem)] bg-white rounded-xl shadow-lg">

    <div class="w-1/4 bg-gray-50 border-r rounded-l-xl flex flex-col">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">Conversations</h2>
            <button id="openModalBtn" class="bg-blue-600 hover:bg-blue-700 text-white font-bold h-9 w-9 rounded-lg flex items-center justify-center shadow-md hover:shadow-lg transition-all" title="New Chat">
                <i class="fa-solid fa-plus"></i>
            </button>
        </div>
        <div class="flex-grow overflow-y-auto">
            <nav class="p-2 space-y-1">
                @forelse($conversations as $conv)
                    <a href="{{ route('admin.chat', $conv) }}" class="flex items-center p-3 rounded-lg truncate transition-colors duration-200 {{ optional($activeConversation)->id === $conv->id ? 'bg-blue-600 text-white font-semibold shadow' : 'text-gray-600 hover:bg-gray-200' }}">
                        <i class="fa-regular fa-message-dots w-5 mr-3"></i>
                        <span>{{ $conv->title }}</span>
                    </a>
                @empty
                    <p class="text-center text-gray-500 p-4">No conversations yet.</p>
                @endforelse
            </nav>
        </div>
    </div>

    <div class="w-3/4 flex flex-col">
        <div id="chat-container" class="flex-grow p-6 overflow-y-auto space-y-6">
            @if($activeConversation)
                @foreach($activeConversation->messages as $message)
                    <div class="flex items-start gap-4 {{ $message->role === 'user' ? 'justify-end' : '' }}">
                        @if($message->role !== 'user')
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white"><i class="fa-solid fa-robot"></i></div>
                        @endif
                        <div class="max-w-2xl px-5 py-3 rounded-2xl {{ $message->role === 'user' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 text-gray-800 rounded-bl-none' }}">
                            <div class="prose">{!! \Illuminate\Support\Str::markdown($message->content) !!}</div>
                        </div>
                         @if($message->role === 'user')
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white"><i class="fa-solid fa-user"></i></div>
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

        <div id="typing-indicator" class="px-6 pb-2 hidden">
            <div class="flex items-start gap-4">
                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white"><i class="fa-solid fa-robot"></i></div>
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

        <div class="p-4 bg-white border-t rounded-b-xl">
             @if(session('error'))
                <p class="text-red-500 text-sm mb-2 text-center">{{ session('error') }}</p>
            @endif
            <form id="chat-form" action="{{ route('admin.chat.store') }}" method="POST">
                @csrf
                <input type="hidden" id="conversation_id_field" name="conversation_id" value="{{ optional($activeConversation)->id }}">
                
                <div class="flex items-center bg-gray-100 rounded-lg p-1.5 border border-gray-200 transition-all duration-200 focus-within:ring-2 focus-within:ring-blue-300">
                    {{-- PERUBAHAN UTAMA ADA DI BARIS INI --}}
                    <input type="text" id="prompt-input" name="prompt" placeholder="Ask me anything..." class="flex-grow bg-transparent border-none focus:ring-0 focus:outline-none text-gray-800 py-2" autocomplete="off" required>
                    <button type="submit" id="send-btn" class="bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 disabled:cursor-not-allowed text-white font-semibold rounded-lg px-5 py-2.5 ml-2 shadow-sm hover:shadow-md transition-all duration-200">
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

        function scrollToBottom() {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        function appendMessage(role, content, isMarkdown = true) {
            const messageWrapper = document.createElement('div');
            messageWrapper.className = `flex items-start gap-4 ${role === 'user' ? 'justify-end' : ''}`;
            
            const avatarHtml = role === 'user'
                ? `<div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white"><i class="fa-solid fa-user"></i></div>`
                : `<div class="flex-shrink-0 w-10 h-10 rounded-full bg-gray-700 flex items-center justify-center text-white"><i class="fa-solid fa-robot"></i></div>`;

            const messageBubble = document.createElement('div');
            messageBubble.className = `max-w-2xl px-5 py-3 rounded-2xl ${role === 'user' ? 'bg-blue-600 text-white rounded-br-none' : 'bg-gray-200 text-gray-800 rounded-bl-none'}`;
            
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
                    window.history.pushState({}, '', `/admin/chat/${data.conversation_id}`);
                    conversationIdField.value = data.conversation_id;
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
    });

    const openModalBtn = document.getElementById('openModalBtn');
    const newChatModal = document.getElementById('newChatModal');
    const cancelBtn = document.getElementById('cancelBtn');

    openModalBtn.addEventListener('click', () => newChatModal.classList.remove('hidden'));
    cancelBtn.addEventListener('click', () => newChatModal.classList.add('hidden'));
    newChatModal.addEventListener('click', (e) => { if (e.target === newChatModal) newChatModal.classList.add('hidden') });
</script>
@endpush
@endsection