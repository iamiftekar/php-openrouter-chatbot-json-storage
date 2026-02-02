<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json");
    $API_KEY = "YOUR_API_KEY_HERE";
    $MEMORY = __DIR__ . "/savechats.json"; //change the json file name as you saved 
    $data = json_decode(file_get_contents("php://input"), true);
    $text = trim($data["message"] ?? "");
    $image = $data["image"] ?? null;
    $isPrivate = $data["private"] ?? false;

    if ($text==="" && !$image) {
        echo json_encode(["reply"=>"Empty request"]);
        exit;
    }

    $mem = [];
    if (file_exists($MEMORY)) {
        $mem = json_decode(file_get_contents($MEMORY), true) ?: [];
    }

    $context = [];
    foreach (array_slice($mem, -6) as $m) {
        $context[] = ["role"=>"user","content"=>$m["user"]];
        $context[] = ["role"=>"assistant","content"=>$m["assistant"]];
    }

    $userContent = [];
    if ($text) $userContent[]=["type"=>"text","text"=>$text];
    if ($image) $userContent[]=["type"=>"image_url","image_url"=>["url"=>$image]];

    $payload = [
        "model"=>"bytedance-seed/seed-1.6-flash", //choose your model paste here the model name 
        "messages"=>array_merge([
            ["role"=>"system","content"=>"You are Nyzo AI from Zividax. Be friendly and helpful. always give a short reply as can as do "]
        ], $context, [
            ["role"=>"user","content"=>$userContent]
        ])
    ];

    $ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,[
        "Authorization: Bearer $API_KEY",
        "Content-Type: application/json"
    ]);
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($payload));

    $result = curl_exec($ch);
    curl_close($ch);

    $json = json_decode($result,true);
    $reply = $json["choices"][0]["message"]["content"] ?? "No response";

    if (!$isPrivate) {
        $mem[]=[
            "time"=>time(),
            "user"=>$text,
            "assistant"=>$reply,
            "image"=>$image
        ];
        file_put_contents($MEMORY,json_encode($mem,JSON_PRETTY_PRINT));
    }

    echo json_encode(["reply"=>$reply]);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>your ai  - Advanced AI Chat Assistant by your company </title>
<meta name="description" content="Nyzo AI: Your intelligent chat assistant powered by advanced AI. Chat, share images, and get instant responses. Created by Team Zividax.">
<link rel="icon" href="your logo icon ">
<style>
*{margin:0;padding:0;box-sizing:border-box}body{background:linear-gradient(135deg,#0b1220,#1a1f3a);color:#fff;font-family:system-ui;height:100vh;overflow:hidden}.container{display:flex;height:100vh}.sidebar{width:280px;background:#0f1525;border-right:1px solid #1e2740;display:flex;flex-direction:column;transition:transform .3s}.sidebar-header{padding:20px;background:#141b2d;border-bottom:1px solid #1e2740}.new-chat-btn{width:100%;padding:12px;background:linear-gradient(135deg,#2563eb,#1d4ed8);border:none;border-radius:10px;color:#fff;font-weight:600;cursor:pointer;transition:all .3s}.new-chat-btn:hover{transform:translateY(-2px);box-shadow:0 4px 12px rgba(37,99,235,.4)}.chat-history{flex:1;overflow-y:auto;padding:10px}.history-item{padding:12px;margin:5px 0;background:#141b2d;border-radius:8px;cursor:pointer;transition:all .2s;border:1px solid transparent}.history-item:hover{background:#1e2740;border-color:#2563eb}.history-item.active{background:#1e2740;border-color:#2563eb}.history-title{font-size:14px;font-weight:500;margin-bottom:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}.history-date{font-size:11px;color:#64748b}.main-content{flex:1;display:flex;flex-direction:column}.chat-header{padding:20px;background:#0f1525;border-bottom:1px solid #1e2740;display:flex;align-items:center;justify-content:space-between}.chat-title{display:flex;align-items:center;gap:12px;font-size:18px;font-weight:600}.logo{width:40px;height:40px;border-radius:50%;object-fit:cover}.mode-toggle{display:flex;gap:10px;align-items:center}.private-mode{display:flex;align-items:center;gap:8px;padding:8px 16px;background:#1e2740;border-radius:20px;font-size:13px;cursor:pointer;transition:all .3s}.private-mode:hover{background:#2563eb}.private-mode.active{background:#dc2626}.toggle-switch{width:36px;height:20px;background:#475569;border-radius:10px;position:relative;transition:background .3s;cursor:pointer}.toggle-switch.active{background:#dc2626}.toggle-switch::after{content:'';position:absolute;width:16px;height:16px;background:#fff;border-radius:50%;top:2px;left:2px;transition:left .3s}.toggle-switch.active::after{left:18px}.messages-container{flex:1;overflow-y:auto;padding:20px;scroll-behavior:smooth}.message{margin:16px 0;display:flex;gap:12px;animation:slideIn .3s}@keyframes slideIn{from{opacity:0;transform:translateY(10px)}to{opacity:1;transform:translateY(0)}}.message.user{justify-content:flex-end}.message-content{max-width:70%;padding:14px 18px;border-radius:18px;line-height:1.5;word-wrap:break-word}.user .message-content{background:linear-gradient(135deg,#2563eb,#1d4ed8);border-bottom-right-radius:4px}.bot .message-content{background:#1e2740;border-bottom-left-radius:4px}.avatar{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:18px;flex-shrink:0}.user .avatar{background:linear-gradient(135deg,#2563eb,#1d4ed8)}.bot .avatar{background:#1e2740;overflow:hidden}.bot .avatar img{width:100%;height:100%;object-fit:cover}.thinking{display:flex;gap:4px;padding:14px 18px}.thinking span{width:8px;height:8px;background:#64748b;border-radius:50%;animation:bounce 1.4s infinite}.thinking span:nth-child(2){animation-delay:.2s}.thinking span:nth-child(3){animation-delay:.4s}@keyframes bounce{0%,80%,100%{transform:translateY(0)}40%{transform:translateY(-10px)}}.input-area{padding:20px;background:#0f1525;border-top:1px solid #1e2740}.input-container{display:flex;gap:10px;align-items:flex-end;max-width:900px;margin:0 auto}.input-wrapper{flex:1;background:#1e2740;border-radius:24px;display:flex;align-items:center;padding:4px 8px;gap:8px}.file-input{display:none}.upload-btn{width:36px;height:36px;border-radius:50%;background:transparent;border:none;color:#64748b;font-size:20px;cursor:pointer;display:flex;align-items:center;justify-content:center;transition:all .2s}.upload-btn:hover{background:#2563eb;color:#fff}#messageInput{flex:1;background:transparent;border:none;color:#fff;padding:12px 8px;font-size:15px;outline:none;resize:none;max-height:120px}#messageInput::placeholder{color:#64748b}.send-btn{width:42px;height:42px;border-radius:50%;background:linear-gradient(135deg,#2563eb,#1d4ed8);border:none;color:#fff;font-size:18px;cursor:pointer;transition:all .3s;display:flex;align-items:center;justify-content:center}.send-btn:hover:not(:disabled){transform:scale(1.05);box-shadow:0 4px 12px rgba(37,99,235,.4)}.send-btn:disabled{opacity:.5;cursor:not-allowed}.footer{text-align:center;padding:12px;font-size:12px;color:#64748b;background:#0f1525;border-top:1px solid #1e2740}.footer a{color:#2563eb;text-decoration:none;cursor:pointer}.footer a:hover{text-decoration:underline}.modal{display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,.8);z-index:1000;align-items:center;justify-content:center}.modal.active{display:flex}.modal-content{background:#1e2740;padding:30px;border-radius:16px;max-width:500px;width:90%;max-height:80vh;overflow-y:auto}.modal-header{font-size:20px;font-weight:600;margin-bottom:20px;padding-bottom:10px;border-bottom:1px solid #334155}.modal-body{line-height:1.8;color:#cbd5e1}.modal-body h3{margin-top:20px;margin-bottom:10px;color:#fff}.close-modal{margin-top:20px;padding:10px 24px;background:#2563eb;border:none;border-radius:8px;color:#fff;cursor:pointer;width:100%}.close-modal:hover{background:#1d4ed8}.cookie-banner{position:fixed;bottom:20px;right:20px;background:#1e2740;padding:20px;border-radius:12px;max-width:400px;box-shadow:0 8px 24px rgba(0,0,0,.4);z-index:999;display:none}.cookie-banner.active{display:block;animation:slideUp .3s}@keyframes slideUp{from{transform:translateY(100px);opacity:0}to{transform:translateY(0);opacity:1}}.cookie-banner h4{margin-bottom:10px}.cookie-banner p{font-size:13px;color:#cbd5e1;margin-bottom:15px;line-height:1.6}.cookie-actions{display:flex;gap:10px}.cookie-actions button{flex:1;padding:8px;border:none;border-radius:6px;cursor:pointer;font-weight:500}.accept-btn{background:#2563eb;color:#fff}.decline-btn{background:#334155;color:#fff}@media(max-width:768px){.sidebar{position:absolute;left:-280px;height:100%;z-index:100}.sidebar.active{transform:translateX(280px)}.message-content{max-width:85%}}::-webkit-scrollbar{width:8px}::-webkit-scrollbar-track{background:#0f1525}::-webkit-scrollbar-thumb{background:#334155;border-radius:4px}::-webkit-scrollbar-thumb:hover{background:#475569}
</style>
</head>
<body>
<div class="container">
<div class="sidebar" id="sidebar">
<div class="sidebar-header"><button class="new-chat-btn" onclick="newChat()">✨ New Chat</button></div>
<div class="chat-history" id="chatHistory"></div>
</div>
<div class="main-content">
<div class="chat-header">
<div class="chat-title"><img src="https://onez5.wordpress.com/wp-content/uploads/2026/01/make-a-logo-of-my-nyzo-ai-who-can-make-genate-images-1.jpg" class="logo"><span>Nyzo AI</span></div>
<div class="mode-toggle">
<div class="private-mode" onclick="togglePrivateMode()"><span id="modeText">Public</span><div class="toggle-switch" id="toggleSwitch"></div></div>
</div>
</div>
<div class="messages-container" id="messagesContainer">
<div class="message bot"><div class="avatar"><img src="https://onez5.wordpress.com/wp-content/uploads/2026/01/make-a-logo-of-my-nyzo-ai-who-can-make-genate-images-1.jpg"></div><div class="message-content">👋 Hello! I'm your  AI from Zividax. How can I assist you today?</div></div>
</div>
<div class="input-area">
<div class="input-container">
<div class="input-wrapper">
<label class="upload-btn">📷<input type="file" class="file-input" id="fileInput" accept="image/*"></label>
<textarea id="messageInput" placeholder="Message Nyzo AI..." rows="1"></textarea>
</div>
<button class="send-btn" onclick="sendMessage()" id="sendBtn">➤</button>
</div>
</div>
<div class="footer">Made with ❤️ by <strong> iftekar</strong> | <a onclick="showPrivacy()">Privacy Policy</a> | <a onclick="toggleSidebar()">☰ History</a></div>
</div>
</div>
<div class="cookie-banner" id="cookieBanner">
<h4>🍪 We use cookies</h4>
<p>We use cookies and collect data to improve your experience. This includes chat history, preferences, and usage analytics.</p>
<div class="cookie-actions"><button class="decline-btn" onclick="declineCookies()">Decline</button><button class="accept-btn" onclick="acceptCookies()">Accept</button></div>
</div>
<div class="modal" id="privacyModal">
<div class="modal-content">
<div class="modal-header">Privacy Policy - your ai</div>
<div class="modal-body">
<p><strong>Effective: January 2026</strong></p>
<h3>Data Collection</h3><p>We collect: chat messages, images, usage patterns, cookies, device info.</p>
<h3>Usage</h3><p>Data is used to provide AI responses, improve service, maintain history, personalize experience.</p>
<h3>Private Mode</h3><p>Private chats are NOT saved to server/history but are processed for responses.</p>
<h3>Storage</h3><p>Public chats: JSON files on server. Browser: localStorage. You can clear data anytime.</p>
<h3>Third Parties</h3><p>We use OpenRouter API for AI processing.</p>
<h3>Your Rights</h3><p>Access, delete, use private mode, decline cookies.</p>
<h3>Contact</h3><p> you@youremail.com.</p>
</div>
<button class="close-modal" onclick="closePrivacy()">Close</button>
</div>
</div>
<script>
let imageData=null,isPrivateMode=false,currentChatId=Date.now(),isThinking=false;window.onload=function(){loadChatHistory();checkCookieConsent();autoResize();messageInput.addEventListener('input',autoResize);messageInput.addEventListener('keydown',function(e){if(e.key==='Enter'&&!e.shiftKey){e.preventDefault();sendMessage()}})};function autoResize(){messageInput.style.height='auto';messageInput.style.height=messageInput.scrollHeight+'px'}function checkCookieConsent(){const c=localStorage.getItem('cookieConsent');if(!c)cookieBanner.classList.add('active')}function acceptCookies(){localStorage.setItem('cookieConsent','accepted');cookieBanner.classList.remove('active')}function declineCookies(){localStorage.setItem('cookieConsent','declined');cookieBanner.classList.remove('active');alert('Some features may be limited')}function togglePrivateMode(){isPrivateMode=!isPrivateMode;toggleSwitch.classList.toggle('active');modeText.textContent=isPrivateMode?'🔒 Private':'Public';if(isPrivateMode)alert('🔒 Private Mode: Messages not saved')}fileInput.onchange=function(e){const f=e.target.files[0];if(!f)return;const r=new FileReader();r.onload=()=>{imageData=r.result;alert('📷 Image attached')};r.readAsDataURL(f)};function addMessage(t,isUser,animate=false){const m=document.createElement('div');m.className='message '+(isUser?'user':'bot');const a=document.createElement('div');a.className='avatar';if(isUser)a.textContent='👤';else a.innerHTML='<img src="https://onez5.wordpress.com/wp-content/uploads/2026/01/make-a-logo-of-my-nyzo-ai-who-can-make-genate-images-1.jpg">';const c=document.createElement('div');c.className='message-content';if(animate&&!isUser)typeWriter(c,t,30);else c.textContent=t;m.appendChild(a);m.appendChild(c);messagesContainer.appendChild(m);messagesContainer.scrollTop=messagesContainer.scrollHeight;return c}function typeWriter(el,txt,spd){let i=0;el.textContent='';function type(){if(i<txt.length){el.textContent+=txt.charAt(i);i++;messagesContainer.scrollTop=messagesContainer.scrollHeight;setTimeout(type,spd)}}type()}function showThinking(){const m=document.createElement('div');m.className='message bot';m.id='thinkingMsg';const a=document.createElement('div');a.className='avatar';a.innerHTML='<img src="https://onez5.wordpress.com/wp-content/uploads/2026/01/make-a-logo-of-my-nyzo-ai-who-can-make-genate-images-1.jpg">';const t=document.createElement('div');t.className='thinking';t.innerHTML='<span></span><span></span><span></span>';m.appendChild(a);m.appendChild(t);messagesContainer.appendChild(m);messagesContainer.scrollTop=messagesContainer.scrollHeight}function removeThinking(){const t=document.getElementById('thinkingMsg');if(t)t.remove()}async function sendMessage(){const t=messageInput.value.trim();if(!t&&!imageData)return;if(isThinking)return;addMessage(t||'📷 Image',true);messageInput.value='';autoResize();isThinking=true;sendBtn.disabled=true;showThinking();try{const r=await fetch('',{method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify({message:t,image:imageData,private:isPrivateMode})});const d=await r.json();removeThinking();addMessage(d.reply||'Error',false,true);if(!isPrivateMode)saveChatToHistory(t,d.reply)}catch(e){removeThinking();addMessage('❌ Connection error',false)}imageData=null;fileInput.value='';isThinking=false;sendBtn.disabled=false}function saveChatToHistory(u,b){const c=localStorage.getItem('cookieConsent');if(c!=='accepted')return;let h=JSON.parse(localStorage.getItem('chatHistory')||'[]');let ch=h.find(c=>c.id===currentChatId);if(!ch){ch={id:currentChatId,title:u.substring(0,30)+(u.length>30?'...':''),messages:[],timestamp:Date.now()};h.push(ch)}ch.messages.push({user:u,bot:b,time:Date.now()});ch.timestamp=Date.now();localStorage.setItem('chatHistory',JSON.stringify(h));loadChatHistory()}function loadChatHistory(){const c=localStorage.getItem('cookieConsent');if(c!=='accepted')return;const h=JSON.parse(localStorage.getItem('chatHistory')||'[]');chatHistory.innerHTML='';h.sort((a,b)=>b.timestamp-a.timestamp).forEach(ch=>{const i=document.createElement('div');i.className='history-item'+(ch.id===currentChatId?' active':'');i.onclick=()=>loadChat(ch.id);const t=document.createElement('div');t.className='history-title';t.textContent=ch.title;const d=document.createElement('div');d.className='history-date';d.textContent=new Date(ch.timestamp).toLocaleDateString();i.appendChild(t);i.appendChild(d);chatHistory.appendChild(i)})}function loadChat(id){const h=JSON.parse(localStorage.getItem('chatHistory')||'[]');const ch=h.find(c=>c.id===id);if(!ch)return;currentChatId=id;messagesContainer.innerHTML='';addMessage('👋 Hello! I\'m your ai o AI from Zividax. How can I assist you today?',false);ch.messages.forEach(m=>{addMessage(m.user,true);addMessage(m.bot,false)});loadChatHistory();if(window.innerWidth<=768)sidebar.classList.remove('active')}function newChat(){currentChatId=Date.now();messagesContainer.innerHTML='';addMessage('👋 Hello! I\'m your ai  AI from Zividax. How can I assist you today?',false);loadChatHistory();if(window.innerWidth<=768)sidebar.classList.remove('active')}function showPrivacy(){privacyModal.classList.add('active')}function closePrivacy(){privacyModal.classList.remove('active')}function toggleSidebar(){sidebar.classList.toggle('active')}
</script>
</body>
</html>
