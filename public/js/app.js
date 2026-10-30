'use strict';
const toastEl=document.querySelector('.toast');
let toastTimeout;
function toast(message){
  if(!toastEl)return;
  toastEl.textContent=message;toastEl.classList.add('show');
  clearTimeout(toastTimeout);toastTimeout=setTimeout(()=>toastEl.classList.remove('show'),5500);
}
const menu=document.getElementById('menu-toggle');
const nav=document.getElementById('main-nav');
menu?.addEventListener('click',()=>{
  const open=nav.classList.toggle('open');menu.setAttribute('aria-expanded',String(open));
});
document.querySelectorAll('[data-password-toggle]').forEach(button=>{
  button.addEventListener('click',()=>{
    const field=document.getElementById(button.dataset.passwordToggle);
    if(!field)return;
    const isHidden=field.type==='password';
    field.type=isHidden?'text':'password';
    button.setAttribute('aria-label',isHidden?'إخفاء كلمة المرور':'إظهار كلمة المرور');
    button.title=isHidden?'إخفاء كلمة المرور':'إظهار كلمة المرور';
    button.querySelector('.ico')?.classList.toggle('eye-open',isHidden);
  });
});
const login=document.getElementById('login-form');
login?.addEventListener('submit',e=>{
 e.preventDefault(); if(!login.reportValidity())return;
 toast('تم التحقق من النموذج. هذه نسخة تجريبية؛ تسجيل الدخول الحقيقي يحتاج ربطها بـ Laravel API.');
});
const signup=document.getElementById('signup-form');
const pw=document.getElementById('signup-password');
const pwConf=document.getElementById('signup-confirm');
pw?.addEventListener('input',()=>{
 const v=pw.value;
 const score=Number(v.length>=8)+Number(/[A-Z]/.test(v))+Number(/[0-9]/.test(v))+Number(/[^a-zA-Z0-9]/.test(v));
 const bar=document.querySelector('.password-meter span');if(bar)bar.style.width=(score*25)+'%';
});
signup?.addEventListener('submit',e=>{
 e.preventDefault();
 if(!signup.reportValidity())return;
 if(pw.value!==pwConf.value){pwConf.setCustomValidity('كلمة المرور غير متطابقة');pwConf.reportValidity();return;}
 pwConf.setCustomValidity('');
 toast('بيانات إنشاء الحساب صحيحة مبدئيًا. يلزم ربط الواجهة بالخادم لإنشاء الحساب فعليًا.');
});
pwConf?.addEventListener('input',()=>pwConf.setCustomValidity(''));
document.querySelectorAll('[data-google-demo]').forEach(btn=>btn.addEventListener('click',()=>toast('تسجيل Google يحتاج تفعيل OAuth وربطه مع Laravel؛ الزر توضيحي حاليًا.')));
document.querySelectorAll('[data-password-reset]').forEach(btn=>btn.addEventListener('click',()=>toast('استرجاع كلمة المرور يتطلب ربط البريد الإلكتروني بالـ Backend.')));
const gym=document.getElementById('gym-form');
gym?.addEventListener('submit',e=>{
 e.preventDefault();if(!gym.reportValidity())return;
 const name=gym.elements.namedItem('gym_name').value.trim();
 const success=document.getElementById('gym-success');
 if(success){success.textContent='';const strong=document.createElement('strong');strong.textContent='طلب '+name+' جاهز للتوصيل بالخادم';success.append(strong,document.createTextNode(' تم التحقق من بيانات النموذج محليًا فقط. لم يتم إرسال الطلب أو تخزينه في قاعدة البيانات. اربط النموذج بـ Laravel لحفظ النادي وإرساله للمراجعة.'));success.classList.add('show');success.scrollIntoView({behavior:'smooth',block:'nearest'});}
});
