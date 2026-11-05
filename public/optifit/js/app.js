
    'use strict';
    const LIVE = window.__OPTIFIT_LIVE || null;
    const STORE_KEY = LIVE ? 'optifit-ui-' + String(LIVE.userId) : 'optifit-glass-admin-demo-v1';
    const initialGyms = [
      {id:101,name:'flyfuyfu',owner:'الكابتن طارق المجالي',area:'عبدون',date:'2026-10-31',complete:85,status:'pending',price:38,phone:'079 000 1001',notes:'بانتظار التحقق من صور الأجهزة وأوقات العمل.'},
      {id:102,name:'أكاديمية بلاتينيوم سبورت',owner:'الكابتن طارق المجالي',area:'خلدا',date:'2026-10-27',complete:80,status:'pending',price:35,phone:'079 000 1002',notes:'بانتظار مراجعة تفاصيل الاشتراك.'},
      {id:103,name:'Power Fit Gym',owner:'أحمد الشمري',area:'الصويفية',date:'2026-10-29',complete:92,status:'pending',price:45,phone:'079 000 1003',notes:'جميع البيانات الأساسية متوفرة.'},
      {id:104,name:'Titan Gym',owner:'محمد العتيبي',area:'الجبيهة',date:'2026-10-25',complete:74,status:'changes',price:30,phone:'079 000 1004',notes:'يرجى إضافة صورة واضحة للقاعة وتأكيد رقم الهاتف.'},
      {id:105,name:'Peak Fitness Center',owner:'سارة الخالد',area:'طبربور',date:'2026-10-23',complete:100,status:'approved',price:50,phone:'079 000 1005',notes:'تم اعتماد الطلب سابقًا.'},
      {id:106,name:'Elite Fitness',owner:'لين أحمد',area:'تلاع العلي',date:'2026-10-21',complete:96,status:'approved',price:40,phone:'079 000 1006',notes:'بيانات مكتملة.'}
    ];
    const initialUsers = [
      {name:'طارق المجالي',email:'tariq@example.com',role:'صاحب جيم',active:'نشط'},
      {name:'أحمد الشمري',email:'ahmad@example.com',role:'صاحب جيم',active:'نشط'},
      {name:'سارة الخالد',email:'sara@example.com',role:'صاحب جيم',active:'نشط'},
      {name:'محمد العتيبي',email:'mohammad@example.com',role:'مستخدم',active:'نشط'},
      {name:'لين أحمد',email:'leen@example.com',role:'مستخدم',active:'نشط'}
    ];
    const initialReports = [
      {id:1, gym:'Titan Gym', title:'الأسعار الظاهرة غير محدّثة',date:'2026-11-01',status:'open'},
      {id:2, gym:'Power Fit Gym', title:'طلب التحقق من ساعات العمل',date:'2026-10-30',status:'open'},
      {id:3, gym:'Elite Fitness',title:'صورة تحتاج إلى تحديث',date:'2026-10-24',status:'done'}
    ];
    const defaults = () => ({ gyms:structuredClone(initialGyms), users:structuredClone(initialUsers), reports:structuredClone(initialReports), logs:[{at:'2026-11-02T09:00:00',message:'فتح لوحة إدارة OptiFit التجريبية'}], permissions:{'اعتماد الصالات':true,'إدارة المستخدمين':true,'عرض البلاغات':true,'تصدير البيانات':true,'عرض سجل التدقيق':true} });
    let db;
    try { db=JSON.parse(localStorage.getItem(STORE_KEY)); if(!db || !Array.isArray(db.gyms)) throw Error('invalid'); } catch { db=defaults(); }
    if (LIVE) {
      db.gyms=LIVE.gyms || [];
      db.users=LIVE.users || [];
      db.reports=[];
      db.logs=[];
    }
    let state={view:document.documentElement.dataset.view||'review',role:document.documentElement.dataset.role||'admin',filter:'pending',search:''};
    let activeGymId=null, activeReportId=null, toastTimer=null;
    const main=document.getElementById('main');
    const backdrop=document.getElementById('backdrop');
    const icon=(n)=>`<svg class="icon" aria-hidden="true"><use href="#i-${n}"/></svg>`;
    const esc=(s)=>String(s??'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    const save=()=>{ try {localStorage.setItem(STORE_KEY,JSON.stringify(db));} catch(e) {console.warn('تعذر الحفظ المحلي',e);} };
    const log=(message)=>{db.logs.unshift({at:new Date().toISOString(),message}); db.logs=db.logs.slice(0,80);save();};
    const datestr=(v)=>{const d=new Date(v);return Number.isNaN(d.getTime())?'—':d.toLocaleString('ar-JO',{dateStyle:'medium',timeStyle:'short'});};
    const statusNames={rejected:'مرفوضة',pending:'قيد المراجعة',changes:'تعديلات مطلوبة',approved:'معتمدة'};
    const statusLabel=(s)=>`<span class="status ${esc(s)}">${statusNames[s]||'غير معروف'}</span>`;
    const badge=(title,sub='')=>`<div class="gym-cell"><span class="gym-badge">${icon('dumbbell2')}</span><span><div class="gym-name">${esc(title)}</div>${sub?`<div class="gym-sub">${esc(sub)}</div>`:''}</span></div>`;
    const toast=(message)=>{document.getElementById('toastText').textContent=message;document.getElementById('toast').classList.add('show');clearTimeout(toastTimer);toastTimer=setTimeout(()=>document.getElementById('toast').classList.remove('show'),3300);};
    const modal=(title,html)=>{document.getElementById('modalTitle').textContent=title;document.getElementById('modalContent').innerHTML=html;backdrop.classList.add('open');document.body.style.overflow='hidden';backdrop.querySelector('.close-btn').focus();};
    const close=()=>{backdrop.classList.remove('open');document.body.style.overflow='';activeGymId=null;activeReportId=null;};
    const header=(title,sub,ic,action='')=>`<div class="hero"><div class="hero-title"><span class="hero-icon">${icon(ic)}</span><div><h1>${title}</h1><p class="subtitle">${sub}</p></div></div>${action}</div>`;
    const stats=()=>{const counts={total:db.gyms.length,pending:db.gyms.filter(g=>g.status==='pending').length,approved:db.gyms.filter(g=>g.status==='approved').length,changes:db.gyms.filter(g=>g.status==='changes').length};return `<div class="stats">${[
      ['إجمالي الصالات',counts.total,'dumbbell2','الطلبات المسجلة'],['قيد المراجعة',counts.pending,'clock','بانتظار فحص الإدارة'],['الصالات المعتمدة',counts.approved,'checkcircle','متاحة للنشر'],['تعديلات مطلوبة',counts.changes,'pencil','بانتظار تعديل البيانات']
    ].map(([label,v,ico,hint])=>`<article class="stat"><div class="stat-head">${esc(label)}${icon(ico)}</div><div class="stat-value">${v.toLocaleString('ar-JO')}</div><div class="stat-hint">${esc(hint)}</div></article>`).join('')}</div>`;};
    const reviewFilters=()=>`<div class="segmented" role="group" aria-label="تصفية حالة الصالات">${[['pending','قيد المراجعة','clock'],['changes','تعديلات مطلوبة','pencil'],['approved','معتمدة','checkcircle'],['rejected','مرفوضة','alert'],['all','كافة الحالات','list']].map(([id,t,i])=>`<button type="button" data-filter="${id}" class="segment ${state.filter===id?'selected':''}" aria-pressed="${state.filter===id}">${icon(i)}${t}</button>`).join('')}</div>`;
    const listGyms=()=>{let rows=db.gyms.filter(g=>state.view==='gyms'||state.filter==='all'||g.status===state.filter);const q=state.search.trim().toLowerCase();if(q)rows=rows.filter(g=>[g.name,g.owner,g.area,statusNames[g.status]].some(v=>String(v).toLowerCase().includes(q)));return rows;};
    const gymRows=(rows)=>rows.map(g=>`<tr><td>${badge(g.name,`#${g.id}`)}</td><td class="nowrap">${esc(g.owner)}</td><td class="muted nowrap">${esc(g.area)}</td><td class="muted nowrap" dir="ltr" style="text-align:right">${esc(g.date)}</td><td><div class="percent" dir="ltr">${g.complete}%</div><div class="progress"><div style="width:${Math.max(0,Math.min(100,g.complete))}%"></div></div></td><td>${statusLabel(g.status)}</td><td><button type="button" class="small-button" data-review="${g.id}">${icon(g.status==='approved'?'eye':'search')}${g.status==='approved'?'عرض التفاصيل':'فحص واعتماد'}</button></td></tr>`).join('');
    const gymsTable=()=>{const rows=listGyms();return `<section class="panel"><div class="data-tools"><div class="tool-right"><div class="input-wrap">${icon('search')}<input id="searchInput" placeholder="ابحث باسم الصالة أو المنطقة أو المالك..." value="${esc(state.search)}" aria-label="البحث في الصالات" autocomplete="off" /></div><span class="count" id="resultCount">${rows.length}</span><span class="panel-note">نتيجة</span></div><div class="tool-right"><button class="small-button" data-action="export" type="button">${icon('download')}تصدير CSV</button>${state.view==='gyms'?`<button class="primary" data-action="addgym" type="button">${icon('plus')}إضافة صالة</button>`:''}</div></div><div class="table-scroll"><table><thead><tr><th>اسم الصالة</th><th>المالك المسؤول</th><th>المنطقة</th><th>تاريخ التقديم</th><th>اكتمال البيانات</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody id="gymBody">${gymRows(rows)}</tbody></table><div class="empty" id="emptyGym" style="${rows.length?'display:none':''}">${icon('search')}<strong>ما في نتائج مطابقة</strong>جرّب تغيير البحث أو فلتر الحالات.</div></div></section><p class="section-note">هذه بيانات تجريبية للمعاينة. أي تعديل بيضل محفوظًا في متصفحك فقط.</p>`;};
    const auditItems=(limit)=>db.logs.slice(0,limit||100).map(a=>`<div class="activity-item"><span class="activity-dot"></span><div><div>${esc(a.message)}</div><div class="activity-date">${datestr(a.at)}</div></div></div>`).join('');
    const renderDashboard=()=>header('لوحة إدارة OptiFit','نظرة عامة على أداء المنصة والطلبات والحالات المسجلة.','grid')+stats()+`<div class="layout-two"><section class="panel"><div class="panel-head"><h2 class="panel-title">آخر الأنشطة</h2><button data-view="audit" type="button" class="small-button">عرض السجل ${icon('arrow')}</button></div><div class="activity">${auditItems(5)}</div></section><section class="panel"><div class="panel-head"><h2 class="panel-title">الوصول السريع</h2><span class="badge-mini">اختصارات الإدارة</span></div><div class="quick-actions">${[['review','طابور مراجعة الصالات','clock'],['gyms','إدارة الصالات','dumbbell2'],['users','عرض المستخدمين','users'],['reports','البلاغات','alert']].map(([v,t,i])=>`<button class="quick-link" data-view="${v}">${icon(i)}${t}${icon('arrow')}</button>`).join('')}</div></section></div><p class="section-note">كل الأرقام في هذه النسخة هي أمثلة وليست بيانات مباشرة من قاعدة البيانات.</p>`;
    const renderReview=()=>header('طابور مراجعة واعتماد الجيمات','تدقيق بيانات الصالات الجديدة وصورها وأسعار اشتراكاتها قبل نشرها للجمهور.','dumbbell2',`<div class="hero-actions">${reviewFilters()}</div>`)+gymsTable();
    const renderGyms=()=>header('إدارة الصالات الرياضية','متابعة طلبات التسجيل وحالات الاعتماد وتعديل بيانات الصالات.','dumbbell2')+gymsTable();
    const renderUsers=()=>header('إدارة المستخدمين','عرض حسابات المستخدمين وأصحاب الصالات في نموذج المنصة.','users')+`<section class="panel"><div class="panel-head"><h2 class="panel-title">المستخدمون المسجلون</h2><span class="count">${db.users.length}</span></div><div class="table-scroll"><table><thead><tr><th>الاسم</th><th>البريد الإلكتروني</th><th>نوع الحساب</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody>${db.users.map((u,i)=>`<tr><td><strong>${esc(u.name)}</strong></td><td class="muted">${esc(u.email)}</td><td>${esc(u.role)}</td><td><span class="status approved">${esc(u.active)}</span></td><td><button class="small-button" data-user="${i}">${icon('eye')} عرض الحساب</button></td></tr>`).join('')}</tbody></table></div></section>`;
    const renderReports=()=>header('إدارة البلاغات','مراجعة الملاحظات المرسلة عن بيانات الصالات الرياضية.','alert')+`<section class="panel"><div class="panel-head"><h2 class="panel-title">البلاغات</h2><span class="count">${db.reports.filter(x=>x.status==='open').length} مفتوح</span></div><div class="table-scroll"><table><thead><tr><th>رقم البلاغ</th><th>الصالة</th><th>الموضوع</th><th>تاريخ البلاغ</th><th>الحالة</th><th>الإجراءات</th></tr></thead><tbody>${db.reports.map(r=>`<tr><td>#${r.id}</td><td>${esc(r.gym)}</td><td>${esc(r.title)}</td><td class="muted nowrap">${esc(r.date)}</td><td><span class="status ${r.status==='open'?'pending':'approved'}">${r.status==='open'?'مفتوح':'مغلق'}</span></td><td><button class="small-button" data-report="${r.id}">${icon('eye')} فتح البلاغ</button></td></tr>`).join('')}</tbody></table></div></section>`;
    const renderAudit=()=>header('سجل التدقيق','سجل العمليات التي تمت داخل النسخة التجريبية من لوحة الإدارة.','file',`<button class="small-button" data-action="exportlog">${icon('download')} تصدير السجل</button>`)+`<section class="panel"><div class="panel-head"><h2 class="panel-title">الأنشطة المسجلة محليًا</h2><span class="count">${db.logs.length}</span></div><div class="activity">${auditItems()}</div></section>`;
    const renderRoleHome=()=>{const isOwner=state.role==='owner';return header(isOwner?'واجهة صاحب الجيم':'واجهة المستخدم',isOwner?'معاينة مختصرة للدور التجريبي — إدارة صفحتك وطلباتك.':'معاينة مختصرة للدور التجريبي — استكشاف النوادي المناسبة. ',isOwner?'building':'users')+`<div class="stats"><article class="stat"><div class="stat-head">${isOwner?'طلبات الصالات':'صالات متاحة'} ${icon('dumbbell2')}</div><div class="stat-value">${db.gyms.length}</div><div class="stat-hint">بيانات تجريبية</div></article><article class="stat"><div class="stat-head">${isOwner?'طلبات مقبولة':'صالات معتمدة'} ${icon('checkcircle')}</div><div class="stat-value">${db.gyms.filter(g=>g.status==='approved').length}</div><div class="stat-hint">قابلة للاستعراض</div></article></div><section class="panel"><div class="panel-head"><h2 class="panel-title">${isOwner?'معاينة بوابة صاحب الجيم':'صالات يمكنك استعراضها'}</h2><span class="badge-mini">دور تجريبي</span></div><div style="padding:24px"><p class="subtitle" style="margin-bottom:20px">الصفحة الأساسية المتكاملة في هذا النموذج هي لوحة مدير النظام. يمكنك العودة إليها وتجربة الموافقات والتصفية والإدارة.</p><button class="primary" data-role="admin">${icon('shield')} العودة إلى مدير النظام</button></div></section>`;};
    /* Screens/fields below correspond to the uploaded User, Gym Owner and chatbot specifications. */
    const activityNames=['Yoga','Swimming','Boxing','Weightlifting','Pilates','Cardio'];
    const facilityNames=['Swimming Pool','Sauna','Parking','Showers','Lockers','Free Weights Area','Women-Only Section','Jacuzzi'];
    const weekdayNames=['الأحد','الاثنين','الثلاثاء','الأربعاء','الخميس','الجمعة','السبت'];
    const appNav={
      user:[['personal','المعلومات الشخصية','users'],['activities','الأنشطة المفضلة','dumbbell2'],['facilities','المرافق المفضلة','checkcircle'],['times','الأوقات المتاحة','clock'],['dashboard','الرئيسية','grid'],['find','بحث النوادي','search'],['results','نتائج البحث','list'],['details','تفاصيل النادي','eye'],['compare','مقارنة النوادي','settings'],['map','الخريطة','pin'],['favorites','المفضلة','checkcircle'],['myreviews','تقييماتي','file'],['ai','المساعد الذكي','spark'],['notifications','الإشعارات','alert'],['profile','حسابي','users'],['notifsettings','إعدادات الإشعارات','settings']],
      owner:[['dashboard','لوحة صاحب الجيم','grid'],['mygyms','نواديّ','dumbbell2'],['edit','إضافة / تعديل نادي','pencil'],['gymfacilities','مرافق النادي','checkcircle'],['gymactivities','أنشطة النادي','dumbbell2'],['memberships','الاشتراكات','file'],['hours','أوقات العمل','clock'],['media','صور النادي','eye'],['reviews','تقييمات النادي','file'],['verification','حالة الاعتماد','shield'],['ai','المساعد الذكي','spark']],
      admin:[['dashboard','لوحة الإدارة','grid'],['review','طابور المراجعة','clock'],['gyms','إدارة الصالات','dumbbell2'],['users','المستخدمين','users'],['reports','البلاغات','alert'],['audit','سجل التدقيق','file'],['ai','المساعد الذكي','spark']]
    };
    const appDefault=()=>({
      preference:{location:'',gender:'',height:'',weight:'',fitnessGoal:'',maxBudget:''},
      activities:[],facilities:[],times:[],favorites:[],reviews:[],compare:[],
      account:{fullName:'',email:''},notificationSettings:{enabled:false,time:'09:00',repeat:'none',start:'',end:''},
      notifications:[], selectedGym:105,selectedOwnerGym:101, search:{location:'',budget:'',activity:'',facility:'',hours:''},
      ownerExtras:{},ownerGymIds:[101,102]
    });
    db.app=Object.assign(appDefault(),db.app||{});
    let app=db.app;
    if (LIVE) {
      app.ownerGymIds=LIVE.ownerGymIds || [];
      app.selectedOwnerGym=app.ownerGymIds.includes(Number(app.selectedOwnerGym)) ? Number(app.selectedOwnerGym) : (app.ownerGymIds[0] || null);
      app.ownerExtras=LIVE.ownerExtras || {};
      app.account={fullName:LIVE.user?.name || '',email:LIVE.user?.email || ''};
      app.selectedGym=db.gyms[0]?.id || null;
    }
    const sessionChats={user:[],owner:[],admin:[]}; // intentionally never stored in DB or localStorage
    const aPanel=(body,heading='')=>`<section class="panel app-panel">${heading?`<div class="panel-head"><h2 class="panel-title">${esc(heading)}</h2></div>`:''}${body}</section>`;
    const inputField=(label,name,value='',type='text',attrs='')=>`<div class="field"><label for="fld-${esc(name)}">${esc(label)}</label><input id="fld-${esc(name)}" name="${esc(name)}" type="${type}" value="${esc(value)}" ${attrs}></div>`;
    const selectField=(label,name,options,value='')=>`<div class="field"><label for="fld-${esc(name)}">${esc(label)}</label><select id="fld-${esc(name)}" name="${esc(name)}">${options.map(o=>{let a=Array.isArray(o)?o:[o,o];return `<option value="${esc(a[0])}" ${String(a[0])===String(value)?'selected':''}>${esc(a[1])}</option>`;}).join('')}</select></div>`;
    const footerAction=(text='حفظ التغييرات')=>`<div class="app-inline-actions"><button class="primary" type="submit">${icon('check')} ${text}</button></div>`;
    const numberKpi=(label,n,ico)=>`<article class="stat"><div class="stat-head">${esc(label)}${icon(ico)}</div><div class="stat-value">${n}</div></article>`;
    const emptyMsg=(text)=>`<p class="app-center">${esc(text)}</p>`;
    const checkGrid=(items,selected)=>`<div class="app-choice-grid">${items.map(v=>`<label class="app-choice"><input type="checkbox" name="choices" value="${esc(v)}" ${selected.includes(v)?'checked':''}>${esc(v)}</label>`).join('')}</div>`;
    const safeCoords=(gym)=>{
      const x=app.ownerExtras[gym.id]||{};
      const lat=Number(x.latitude), lng=Number(x.longitude);
      if(Number.isFinite(lat)&&lat>29&&lat<34&&Number.isFinite(lng)&&lng>34&&lng<40)return [lat,lng];
      return null;
    };
    const currentGym=()=>db.gyms.find(g=>g.id===Number(app.selectedGym))||db.gyms[0];
    const currentOwnerGym=()=>db.gyms.find(g=>g.id===Number(app.selectedOwnerGym)&&app.ownerGymIds.includes(g.id))||db.gyms.find(g=>app.ownerGymIds.includes(g.id));
    const gymExtra=(id)=>app.ownerExtras[id]||(app.ownerExtras[id]={description:'',city:'عمّان',address:'',latitude:'',longitude:'',website_url:'',facilities:[],activities:[],memberships:[],hours:[],images:[]});
    const gymPlan=(g)=>{const e=gymExtra(g.id);return (e.memberships||[]).find(m=>Number(m.duration)===1)?.price??g.price;};
    const gymRating=(id)=>{const list=app.reviews.filter(r=>r.gymId===id);return list.length?(list.reduce((a,r)=>a+Number(r.rating),0)/list.length).toFixed(1):'—';};
    const reason=(g)=>{const e=gymExtra(g.id),bits=[];if(app.preference.location&&[g.area,e.city].some(s=>(s||'').includes(app.preference.location)))bits.push('الموقع مناسب');if(app.preference.maxBudget&&Number(gymPlan(g))<=Number(app.preference.maxBudget))bits.push('ضمن الميزانية');if(app.facilities.length&&app.facilities.some(x=>e.facilities.includes(x)))bits.push('مرافق مطلوبة');if(app.activities.length&&app.activities.some(x=>e.activities.includes(x)))bits.push('أنشطة مفضلة');return bits.length?bits.join(' · '):'يمكنك مقارنة السعر والموقع والمرافق';};
    const match=(g)=>{const e=gymExtra(g.id);let score=0;if(Number(gymPlan(g))<=Number(app.preference.maxBudget)&&app.preference.maxBudget)score+=3;if(app.preference.location&&(g.area+' '+e.city).includes(app.preference.location))score+=3;score+=app.facilities.filter(x=>e.facilities.includes(x)).length;score+=app.activities.filter(x=>e.activities.includes(x)).length;return score;};
    const availableGyms=()=>db.gyms.filter(g=>g.status==='approved');
    const selectedResults=()=>availableGyms().filter(g=>{let q=app.search,e=gymExtra(g.id);return (!q.location||(g.area+' '+e.city+' '+g.name).includes(q.location))&&(!q.budget||Number(gymPlan(g))<=Number(q.budget))&&(!q.activity||e.activities.includes(q.activity))&&(!q.facility||e.facilities.includes(q.facility))&&(!q.hours||(e.hours||[]).some(h=>h.day===q.hours));}).sort((a,b)=>match(b)-match(a));
    const gymCard=(g,ownerMode=false)=>{const e=gymExtra(g.id);return `<article class="app-card"><div class="app-row">${badge(g.name)}${ownerMode?statusLabel(g.status):`<span class="app-chip">${gymRating(g.id)==='—'?'بدون تقييم':'★ '+gymRating(g.id)}</span>`}</div><div class="card-meta">${icon('pin')} ${esc([e.city||'عمّان',g.area].filter(Boolean).join(' – '))}<div>الاشتراك الشهري: ${esc(gymPlan(g))} د.أ</div>${!ownerMode?`<div>سبب الاقتراح: ${esc(reason(g))}</div>`:''}</div><div class="card-actions">${ownerMode?`<button class="small-button" data-app="owner-select" data-id="${g.id}">${icon('eye')} عرض / تعديل</button><button class="small-button warning-button" data-app="owner-delete" data-id="${g.id}">${icon('close')} حذف</button>`:`<button class="small-button" data-app="gym-detail" data-id="${g.id}">${icon('eye')} التفاصيل</button><button class="small-button" data-app="favorite" data-id="${g.id}">${icon('checkcircle')} ${app.favorites.includes(g.id)?'إزالة من المفضلة':'أضف للمفضلة'}</button><button class="small-button" data-app="compare" data-id="${g.id}">${icon('settings')} ${app.compare.includes(g.id)?'إزالة من المقارنة':'مقارنة'}</button>`}</div></article>`;};
    const userSteps=()=>`<div class="app-steps">${[['personal','المعلومات الشخصية'],['activities','الأنشطة'],['facilities','المرافق'],['times','الأوقات']].map(([id,label])=>`<button class="app-step ${state.view===id?'active':''}" type="button" data-go="${id}">${label}</button>`).join('')}</div>`;
    const renderUser=()=>{
      const p=app.preference;
      switch(state.view){
      case 'personal': return header('المعلومات الشخصية','حدد بياناتك الرياضية وموقعك وميزانيتك للبحث المناسب.','users')+userSteps()+aPanel(`<form data-app-form="preference"><div class="app-form-grid">${inputField('المنطقة / الموقع','location',p.location,'text','required')}${selectField('الجنس','gender',[['','اختر الجنس'],['male','ذكر'],['female','أنثى']],p.gender)}${inputField('الطول (سم)','height',p.height,'number','min="50" max="260" step="0.1"')}${inputField('الوزن (كغ)','weight',p.weight,'number','min="15" max="450" step="0.1"')}${selectField('الهدف الرياضي','fitnessGoal',[['','اختر الهدف'],['bulk','تضخيم'],['cut','تنشيف'],['fitness','لياقة عامة']],p.fitnessGoal)}${inputField('الميزانية القصوى (دينار)','maxBudget',p.maxBudget,'number','min="0" step="0.01"')}</div>${footerAction('حفظ والمتابعة')}</form>`,'البيانات والتفضيلات');
      case 'activities': return header('الأنشطة المفضلة','اختر الرياضات التي تفضل توفرها في النادي.','dumbbell2')+userSteps()+aPanel(`<form data-app-form="activities">${checkGrid(activityNames,app.activities)}${footerAction('حفظ والمتابعة')}</form>`,'اختر الأنشطة');
      case 'facilities': return header('المرافق المفضلة','اختر المرافق والخدمات التي تبحث عنها.','checkcircle')+userSteps()+aPanel(`<form data-app-form="facilities">${checkGrid(facilityNames,app.facilities)}${footerAction('حفظ والمتابعة')}</form>`,'اختر المرافق');
      case 'times': return header('الأوقات المتاحة','حدد أيام وأوقات التمرين، ويمكن إضافة أكثر من فترة.','clock')+userSteps()+aPanel(`<form data-app-form="add-time"><div class="app-form-grid">${selectField('اليوم','day',weekdayNames.map(x=>[x,x]))}${inputField('من الساعة','start','08:00','time','required')}${inputField('إلى الساعة','end','10:00','time','required')}</div>${footerAction('إضافة فترة')}</form><div class="app-table-wrap"><table><thead><tr><th>اليوم</th><th>من</th><th>إلى</th><th>الإجراءات</th></tr></thead><tbody>${app.times.map((t,i)=>`<tr><td>${esc(t.day)}</td><td>${esc(t.start)}</td><td>${esc(t.end)}</td><td><button class="small-button warning-button" data-app="remove-time" data-id="${i}">حذف</button></td></tr>`).join('')}</tbody></table></div><div class="app-inline-actions"><button class="primary" data-go="dashboard">الانتقال إلى الرئيسية ${icon('arrow')}</button></div>`,'مواعيد التمرين');
      case 'dashboard': return header('لوحة المستخدم','نوادي مناسبة حسب تفضيلاتك واختصارات خدمات المنصة.','grid')+`<div class="stats">${numberKpi('نوادٍ معتمدة',availableGyms().length,'dumbbell2')}${numberKpi('المفضلة',app.favorites.length,'checkcircle')}${numberKpi('أوقات التمرين',app.times.length,'clock')}${numberKpi('تقييماتي',app.reviews.length,'file')}</div>`+aPanel(`<div class="app-inline-actions"><button class="small-button" data-go="personal">${icon('users')} تعديل تفضيلاتي</button><button class="small-button" data-go="find">${icon('search')} البحث</button><button class="small-button" data-go="favorites">${icon('checkcircle')} المفضلة</button><button class="small-button" data-go="ai">${icon('spark')} المساعد الذكي</button></div><p class="app-muted">${esc(p.location||'لم تحدد منطقة بعد')} · ${p.maxBudget?`الميزانية القصوى ${esc(p.maxBudget)} دينار`:'لم تحدد الميزانية بعد'}</p>`,'مرحبًا بك')+aPanel(`<div class="app-grid">${availableGyms().slice().sort((a,b)=>match(b)-match(a)).slice(0,3).map(g=>gymCard(g)).join('')}</div>`,'نوادٍ مقترحة وقريبة حسب البيانات المتاحة');
      case 'find': return header('البحث عن النوادي','ابحث بحسب الموقع والميزانية والأنشطة والمرافق وساعات العمل.','search')+aPanel(`<form data-app-form="gym-search"><div class="app-filter">${inputField('المنطقة أو اسم النادي','location',app.search.location)}${inputField('أعلى سعر شهري (د.أ)','budget',app.search.budget,'number','min="0"')}${selectField('النشاط','activity',[['','جميع الأنشطة'],...activityNames.map(x=>[x,x])],app.search.activity)}${selectField('المرفق','facility',[['','جميع المرافق'],...facilityNames.map(x=>[x,x])],app.search.facility)}${selectField('يوم العمل','hours',[['','جميع الأيام'],...weekdayNames.map(x=>[x,x])],app.search.hours)}</div>${footerAction('عرض النتائج')}</form>`,'فلاتر البحث');
      case 'results': return header('نتائج البحث والترشيحات','النتائج المعتمدة مرتبة بقواعد ملاءمة توضيحية وليست نموذج ذكاء اصطناعي مدربًا.','list',`<button class="small-button" data-go="find">${icon('search')} تغيير الفلاتر</button>`)+aPanel(selectedResults().length?`<div class="app-grid">${selectedResults().map(g=>gymCard(g)).join('')}</div>`:emptyMsg('لا توجد نوادٍ مطابقة. غيّر فلاتر البحث.'),`نتائج البحث (${selectedResults().length})`);
      case 'details': {const g=currentGym(),e=gymExtra(g.id);return header('تفاصيل النادي','معلومات النادي والتقييمات والأسعار والمرافق وساعات العمل.','eye')+aPanel(`<div class="app-row">${badge(g.name,[e.city,g.area].filter(Boolean).join(' — '))}<button class="small-button" data-app="favorite" data-id="${g.id}">${icon('checkcircle')}${app.favorites.includes(g.id)?'إزالة من المفضلة':'أضف للمفضلة'}</button></div><p class="app-muted">${esc(e.description||'لا يوجد وصف مضاف بعد.')}</p><div class="info-grid">${[['العنوان',e.address||g.area],['الهاتف',g.phone||'—'],['الموقع الإلكتروني',e.website_url||'—'],['تقييم المستخدمين',gymRating(g.id)],['المدينة',e.city||'—'],['الموقع الجغرافي',safeCoords(g)?safeCoords(g).join(', '):'غير محدد']].map(([k,v])=>`<div class="detail"><span class="label">${esc(k)}</span><span class="value">${esc(v)}</span></div>`).join('')}</div><h3 class="app-block-title">المرافق</h3>${(e.facilities||[]).map(x=>`<span class="app-chip">${esc(x)}</span>`).join('')||'<p class="app-muted">لم تُحدد المرافق.</p>'}<h3 class="app-block-title">الأنشطة</h3>${(e.activities||[]).map(x=>`<span class="app-chip">${esc(x)}</span>`).join('')||'<p class="app-muted">لم تُحدد الأنشطة.</p>'}<div class="app-divider"></div><h3 class="app-block-title">الاشتراكات</h3>${(e.memberships||[]).length?`<div class="app-table-wrap"><table><thead><tr><th>المدة بالأشهر</th><th>السعر بالدينار</th></tr></thead><tbody>${e.memberships.map(m=>`<tr><td>${esc(m.duration)}</td><td>${esc(m.price)}</td></tr>`).join('')}</tbody></table></div>`:`<p class="app-muted">الاشتراك الشهري التجريبي: ${esc(g.price)} د.أ</p>`}<h3 class="app-block-title">أوقات العمل</h3>${(e.hours||[]).map(h=>`<span class="app-chip muted">${esc(h.day)}: ${esc(h.open)}–${esc(h.close)}</span>`).join('')||'<p class="app-muted">لا توجد ساعات عمل مدخلة.</p>'}<div class="app-divider"></div><h3 class="app-block-title">تقييمات النادي</h3>${app.reviews.filter(r=>r.gymId===g.id).map(r=>`<p class="app-muted"><span class="app-review-stars">${'★'.repeat(Number(r.rating))}</span> ${esc(r.comment)}</p>`).join('')||'<p class="app-muted">لا توجد تقييمات بعد.</p>'}<form data-app-form="review"><input type="hidden" name="gymId" value="${g.id}">${selectField('تقييمك','rating',[[5,'5 نجوم'],[4,'4 نجوم'],[3,'3 نجوم'],[2,'نجمتان'],[1,'نجمة']])}<div class="field"><label for="reviewComment">التعليق</label><textarea id="reviewComment" name="comment" required maxlength="700"></textarea></div>${footerAction('نشر التقييم')}</form>`,'معلومات النادي');}
      case 'compare': {const list=availableGyms();const chosen=list.filter(g=>app.compare.includes(g.id));return header('مقارنة النوادي','قارن ناديين أو أكثر بالسعر والموقع والمرافق والأنشطة والدوام والتقييم.','settings')+aPanel(`<div class="app-choice-grid">${list.map(g=>`<label class="app-choice"><input type="checkbox" data-app-check="compare" value="${g.id}" ${app.compare.includes(g.id)?'checked':''}>${esc(g.name)}</label>`).join('')}</div>${chosen.length>1?`<div class="app-table-wrap"><table><thead><tr><th>المعيار</th>${chosen.map(g=>`<th>${esc(g.name)}</th>`).join('')}</tr></thead><tbody>${[['الموقع',g=>g.area],['المسافة',g=>safeCoords(g)?'تُحسب من الخريطة عند توفر موقع المستخدم':'غير متاح'],['سعر الشهر',g=>gymPlan(g)+' د.أ'],['المرافق',g=>gymExtra(g.id).facilities.join('، ')||'—'],['الأنشطة',g=>gymExtra(g.id).activities.join('، ')||'—'],['الدوام',g=>gymExtra(g.id).hours.map(h=>h.day+' '+h.open+'-'+h.close).join('، ')||'—'],['التقييم',g=>gymRating(g.id)]].map(([label,fn])=>`<tr><td><strong>${label}</strong></td>${chosen.map(g=>`<td>${esc(fn(g))}</td>`).join('')}</tr>`).join('')}</tbody></table></div>`:emptyMsg('اختر ناديين على الأقل للمقارنة.')}`,'النوادي المحددة');}
      case 'map': return header('خريطة النوادي','عرض مواقع النوادي المعتمدة وتفاصيلها عند اختيار العلامة.','pin')+aPanel(`<div id="optiMap" class="app-map"><div class="app-map-fallback">جاري عرض الخريطة عند توفر اتصال بالإنترنت.</div></div><p class="app-status-note">لن تظهر علامات النوادي التي لم يُدخل صاحبها إحداثياتها. إذن الموقع يطلبه المتصفح عند استخدام خيار موقعي.</p><div class="app-inline-actions"><button class="small-button" data-app="geolocate">${icon('pin')} موقعي الحالي</button></div>`,'مواقع النوادي');
      case 'favorites': return header('النوادي المفضلة','يمكنك مشاهدة النوادي المحفوظة وحذفها من المفضلة.','checkcircle')+aPanel(`<div class="app-grid">${availableGyms().filter(g=>app.favorites.includes(g.id)).map(g=>gymCard(g)).join('')||emptyMsg('لم تضف نوادي إلى المفضلة بعد.')}</div>`);
      case 'myreviews': return header('تقييماتي','عرض التقييمات التي كتبتها وتعديلها أو حذفها.','file')+aPanel(`<div class="app-table-wrap"><table><thead><tr><th>النادي</th><th>التقييم</th><th>التعليق</th><th>الإجراءات</th></tr></thead><tbody>${app.reviews.map((r,i)=>`<tr><td>${esc(db.gyms.find(g=>g.id===r.gymId)?.name||'—')}</td><td>★ ${esc(r.rating)}</td><td>${esc(r.comment)}</td><td><button class="small-button" data-app="review-edit" data-id="${i}">تعديل</button> <button class="small-button warning-button" data-app="review-delete" data-id="${i}">حذف</button></td></tr>`).join('')}</tbody></table>${app.reviews.length?'':emptyMsg('ما كتبت تقييمات بعد.')}</div>`);
      case 'ai': return renderAppChat('user');
      case 'notifications': return header('الإشعارات','عرض الإشعارات والتذكيرات وحالتها.','alert',`<button class="small-button" data-go="notifsettings">${icon('settings')} إعدادات الإشعارات</button>`)+aPanel(app.notifications.length?app.notifications.map(n=>`<div class="app-notification"><strong>${esc(n.title)}</strong><div class="app-muted">${esc(n.message)}</div><div class="app-chip muted">${esc(n.type)} · ${esc(n.status)}</div></div>`).join(''):emptyMsg('لا توجد إشعارات حاليًا.'),'الإشعارات');
      case 'profile': return header('حسابي الشخصي','تعديل الاسم والبريد الإلكتروني وكلمة المرور.','users')+aPanel(`<form data-app-form="account"><div class="app-form-grid">${inputField('الاسم الكامل','fullName',app.account.fullName,'text','required')}${inputField('البريد الإلكتروني','email',app.account.email,'email','required')}${inputField('كلمة المرور الجديدة','password','','password','minlength="8" autocomplete="new-password"')}</div><p class="app-muted">هذه واجهة تجريبية؛ كلمة المرور لا تُخزَّن محليًا ولا تُغيَّر في Laravel دون ربط المصادقة.</p>${footerAction()}</form>`,'إعدادات الحساب');
      case 'notifsettings': {const s=app.notificationSettings;return header('إعدادات الإشعارات','تفعيل التذكيرات وتحديد التكرار والوقت والفترة.','settings')+aPanel(`<form data-app-form="notifsettings"><label class="app-choice"><input type="checkbox" name="enabled" ${s.enabled?'checked':''}>تفعيل الإشعارات والتذكيرات</label><div class="app-form-grid">${inputField('وقت التذكير','time',s.time,'time')}${selectField('التكرار','repeat',[['none','بدون تكرار'],['daily','يومي'],['weekly','أسبوعي']],s.repeat)}${inputField('تاريخ البداية','start',s.start,'date')}${inputField('تاريخ النهاية','end',s.end,'date')}</div>${footerAction()}</form>`,'خيارات الإشعارات');}
      default:return header('المستخدم','اختر صفحة من القائمة.','users');
      }
    };
    const ownerSelector=()=>{
      const own=db.gyms.filter(g=>app.ownerGymIds.includes(g.id));
      return `<div class="app-inline-actions"><label for="ownerGymSelect" class="app-muted">النادي الحالي:</label><select class="app-select" id="ownerGymSelect" data-app-select="owner-gym">${own.map(g=>`<option value="${g.id}" ${Number(app.selectedOwnerGym)===g.id?'selected':''}>${esc(g.name)}</option>`).join('')}</select></div>`;
    };
    const renderOwner=()=>{
      const g=currentOwnerGym(),e=g?gymExtra(g.id):null,own=db.gyms.filter(x=>app.ownerGymIds.includes(x.id));
      switch(state.view){
      case 'dashboard':return header('لوحة صاحب الجيم','ملخص نواديك ومراجعاتها وتقييمات المستخدمين.','grid')+`<div class="stats">${numberKpi('إجمالي النوادي',own.length,'dumbbell2')}${numberKpi('قيد المراجعة',own.filter(x=>x.status==='pending').length,'clock')}${numberKpi('معتمدة',own.filter(x=>x.status==='approved').length,'checkcircle')}${numberKpi('مرفوضة',own.filter(x=>x.status==='rejected').length,'alert')}</div>`+aPanel(`<div class="app-inline-actions"><button class="primary" data-go="mygyms">${icon('dumbbell2')} نواديّ</button><button class="small-button" data-app="owner-new">${icon('plus')} إضافة نادي جديد</button><button class="small-button" data-go="reviews">${icon('file')} عرض التقييمات</button></div><h3 class="app-block-title">أحدث التقييمات</h3>${app.reviews.filter(r=>app.ownerGymIds.includes(r.gymId)).slice(-4).reverse().map(r=>`<div class="app-notification"><span class="app-review-stars">${'★'.repeat(Number(r.rating))}</span> ${esc(r.comment)}</div>`).join('')||emptyMsg('لا توجد تقييمات حتى الآن.')}`);
      case 'mygyms':return header('نواديّ','عرض وإدارة جميع الصالات التابعة لحسابك.','dumbbell2',`<button class="primary" data-app="owner-new">${icon('plus')} إضافة نادي جديد</button>`)+aPanel(`<div class="app-grid">${own.map(x=>gymCard(x,true)).join('')||emptyMsg('لا توجد نوادٍ مسجلة.')}</div>`,'النوادي المسجلة');
      case 'edit':return header('إضافة / تعديل النادي','بيانات النادي وموقعه ورقم تواصله، وحالة الاعتماد يحددها المدير.','pencil')+aPanel((g?ownerSelector():emptyMsg('أضف ناديًا أولاً.'))+`<form data-app-form="owner-edit"><div class="app-form-grid">${inputField('اسم النادي','gym_name',g?.name||'','text','required')}${inputField('المدينة','city',e?.city||'')}${inputField('المنطقة','area',g?.area||'')}${inputField('العنوان','address',e?.address||'')}${inputField('خط العرض','latitude',e?.latitude||'','number','step="any" min="-90" max="90"')}${inputField('خط الطول','longitude',e?.longitude||'','number','step="any" min="-180" max="180"')}${inputField('رقم الهاتف','phone',g?.phone||'','tel')}${inputField('الموقع الإلكتروني','website_url',e?.website_url||'','url')}</div><div class="field"><label for="gymDesc">وصف النادي</label><textarea id="gymDesc" name="description">${esc(e?.description||'')}</textarea></div><p class="app-muted">المالك يُحدد من الحساب، والحالة تبدأ قيد المراجعة. لا يستطيع صاحب الجيم تغيير حالة الموافقة.</p><div class="app-inline-actions"><button class="small-button" type="button" data-app="map-location">${icon('pin')} تحديد الموقع على الخريطة</button><button class="primary" type="submit">${icon('check')} حفظ النادي</button><button class="small-button" type="button" data-go="mygyms">إلغاء</button></div></form>`,'معلومات النادي');
      case 'gymfacilities':return header('مرافق النادي','إضافة أو إزالة مرافق النادي المسجل.','checkcircle')+aPanel(ownerSelector()+`<form data-app-form="owner-facilities">${checkGrid(facilityNames,e?.facilities||[])}${footerAction('حفظ المرافق')}</form>`,'المرافق');
      case 'gymactivities':return header('أنشطة النادي','إضافة أو إزالة الرياضات والأنشطة المقدمة في النادي.','dumbbell2')+aPanel(ownerSelector()+`<form data-app-form="owner-activities">${checkGrid(activityNames,e?.activities||[])}${footerAction('حفظ الأنشطة')}</form>`,'الأنشطة');
      case 'memberships':return header('إدارة الاشتراكات','أضف وعدل واحذف أسعار الاشتراك ومددها بالدينار الأردني.','file')+aPanel(ownerSelector()+`<form data-app-form="membership"><div class="app-form-grid">${inputField('المدة (بالأشهر)','duration','','number','min="1" required')}${inputField('السعر (دينار أردني)','price','','number','min="0" step="0.01" required')}</div>${footerAction('إضافة اشتراك')}</form><div class="app-table-wrap"><table><thead><tr><th>المدة (أشهر)</th><th>السعر (د.أ)</th><th>الإجراءات</th></tr></thead><tbody>${(e?.memberships||[]).map((m,i)=>`<tr><td>${esc(m.duration)}</td><td>${esc(m.price)}</td><td><button class="small-button" data-app="membership-edit" data-id="${i}">تعديل</button> <button class="small-button warning-button" data-app="membership-delete" data-id="${i}">حذف</button></td></tr>`).join('')}</tbody></table></div>`,'قائمة الاشتراكات');
      case 'hours':return header('أوقات عمل النادي','تحديد أيام وساعات فتح وإغلاق النادي.','clock')+aPanel(ownerSelector()+`<form data-app-form="hours"><div class="app-form-grid">${selectField('اليوم','day',weekdayNames)}${inputField('الفتح','open','07:00','time','required')}${inputField('الإغلاق','close','23:00','time','required')}</div>${footerAction('حفظ يوم العمل')}</form><div class="app-table-wrap"><table><thead><tr><th>اليوم</th><th>من</th><th>إلى</th><th>الإجراءات</th></tr></thead><tbody>${(e?.hours||[]).map((h,i)=>`<tr><td>${esc(h.day)}</td><td>${esc(h.open)}</td><td>${esc(h.close)}</td><td><button class="small-button warning-button" data-app="hours-delete" data-id="${i}">حذف</button></td></tr>`).join('')}</tbody></table></div>`,'الدوام الأسبوعي');
      case 'media':return header('صور النادي','رفع واستعراض وحذف صور النادي، دون فيديوهات.','eye')+aPanel(ownerSelector()+`<div class="app-upload"><label for="gymUpload">رفع صور النادي (حتى 10 صور)</label><div class="app-inline-actions"><input id="gymUpload" type="file" accept="image/png,image/jpeg,image/webp" multiple aria-label="رفع صور النادي"></div><p class="app-muted">في هذه المعاينة تُحفظ الصور الصغيرة داخل المتصفح فقط؛ رفعها إلى Laravel Storage يحتاج ربطًا بالخادم.</p></div><div class="app-photo-grid">${(e?.images||[]).map((im,i)=>`<div class="app-photo"><img src="${im}" alt="صورة للنادي ${i+1}"><button class="small-button warning-button" data-app="photo-delete" data-id="${i}">حذف</button></div>`).join('')}</div>`,'معرض الصور');
      case 'reviews':{const rating=state.ownerRating||'all';const all=app.reviews.filter(r=>app.ownerGymIds.includes(r.gymId));const filt=all.filter(r=>rating==='all'||Number(r.rating)===Number(rating));return header('تقييمات النوادي','عرض تقييمات المستخدمين وفرزها؛ لا يمكن لصاحب النادي تعديلها أو حذفها.','file')+aPanel(`<div class="app-inline-actions">${selectField('فلترة حسب النجوم','ratingFilter',[['all','جميع التقييمات'],[5,'5 نجوم'],[4,'4 نجوم'],[3,'3 نجوم'],[2,'نجمتان'],[1,'نجمة']],rating)}</div><div class="app-table-wrap"><table><thead><tr><th>النادي</th><th>التقييم</th><th>التعليق</th><th>تاريخ الإضافة</th></tr></thead><tbody>${filt.map(r=>`<tr><td>${esc(db.gyms.find(g=>g.id===r.gymId)?.name)}</td><td>★ ${esc(r.rating)}</td><td>${esc(r.comment)}</td><td>${esc(r.date||'—')}</td></tr>`).join('')}</tbody></table>${filt.length?'':emptyMsg('لا توجد تقييمات مطابقة.')}</div>`,'التقييمات');}
      case 'verification':return header('حالة اعتماد النوادي','متابعة قرارات مدير النظام، دون القدرة على تغييرها.','shield')+aPanel(`<div class="app-table-wrap"><table><thead><tr><th>اسم النادي</th><th>حالة الاعتماد</th></tr></thead><tbody>${own.map(x=>`<tr><td>${esc(x.name)}</td><td>${statusLabel(x.status)}</td></tr>`).join('')}</tbody></table></div>`,'طلبات الموافقة');
      case 'ai':return renderAppChat('owner');
      default:return header('صاحب الجيم','اختر صفحة من القائمة.','building');
      }
    };
    const chatEndpoint={user:'/api/user/chat',owner:'/api/owner/chat',admin:'/api/admin/chat'};
    const renderAppChat=(role)=>{
      const roleName={user:'المستخدم',owner:'صاحب الجيم',admin:'مدير النظام'}[role];
      const scope={user:'اللياقة العامة والتغذية ومعلومات النوادي وأسباب ترشيحها',owner:'كيفية استخدام المنصة وإدارة النوادي والاشتراكات والمرافق وساعات العمل',admin:'لوحة التحكم وإدارة المستخدمين والنوادي والتحقق والصلاحيات'}[role];
      return header('المساعد الذكي','مساعد مخصص لقسم '+roleName+' — '+scope+'.','spark')+aPanel(`<div class="app-chat"><div class="app-row"><span class="app-muted">المحادثة مؤقتة خلال الجلسة، ولا تُحفظ في قاعدة البيانات.</span><button class="small-button" data-app="new-chat">${icon('plus')} محادثة جديدة</button></div><div class="app-chat-history" id="chatHistory">${sessionChats[role].length?sessionChats[role].map(m=>`<div class="app-bubble ${m.role==='user'?'user':''}">${esc(m.text)}</div>`).join(''):`<div class="app-center">اسأل المساعد عن ${esc(scope)}.</div>`}</div><form class="app-chat-form" data-app-form="chat"><input name="message" required maxlength="3000" autocomplete="off" placeholder="اكتب سؤالك هنا..." aria-label="اكتب رسالة إلى المساعد"><button class="primary" type="submit">${icon('arrow')} إرسال</button></form></div><p class="app-status-note">واجهة جاهزة للربط بمسار <code dir="ltr">${chatEndpoint[role]}</code>. تحتاج خدمة AI موحّدة وتحقق صلاحيات من Laravel؛ لا توجد استجابات AI وهمية.</p>`,'المحادثة');
    };
    const renderAdminAI=()=>renderAppChat('admin');
    function fillAppNav(){
      const links=appNav[state.role];
      document.getElementById('navItems').innerHTML=links.map(([key,label,ic])=>`<button class="nav-item ${state.view===key?'active':''}" data-view="${key}" type="button">${icon(ic)} ${esc(label)}</button>`).join('');
      document.querySelector('.permission-btn').style.display=state.role==='admin'?'':'none';
      document.querySelector('.catalog').textContent='';
      document.querySelector('.catalog').innerHTML=icon('grid')+' فهرس الشاشات';
    }

  // Static multi-page export: all screens have their own editable .html file.
  // The demo role buttons are NOT a real authentication mechanism.
  function staticLink(role,view){
    return new URL('../'+encodeURIComponent(role)+'/'+encodeURIComponent(view)+'.html', location.href).href;
  }
  document.addEventListener('click',function(event){
    const btn=event.target.closest('button');
    if(!btn)return;
    let targetRole=null,targetView=null;
    if(btn.hasAttribute('data-role')){targetRole=btn.dataset.role;targetView=targetRole==='admin'?'review':'dashboard';}
    else if(btn.hasAttribute('data-view')){targetRole=state.role;targetView=btn.dataset.view;}
    else if(btn.hasAttribute('data-go')){targetRole=state.role;targetView=btn.dataset.go;}
    else if(btn.hasAttribute('data-catalog-route')){[targetRole,targetView]=btn.dataset.catalogRoute.split(':');}
    else if(btn.hasAttribute('data-catalog-view')){targetRole='admin';targetView=btn.dataset.catalogView;}
    if(targetRole && targetView && appNav[targetRole]?.some(entry=>entry[0]===targetView)){
      event.preventDefault(); event.stopImmediatePropagation();
      location.href=staticLink(targetRole,targetView);
    }
  },true);

    function showAppCatalog(){
      const part=(role,title)=>`<h3 class="app-block-title">${title}</h3><div class="app-choice-grid">${appNav[role].map(([v,t,i])=>`<button class="small-button" data-catalog-route="${role}:${v}">${icon(i)} ${esc(t)}</button>`).join('')}</div>`;
      modal('فهرس الشاشات المعتمدة',part('user','المستخدم')+part('owner','صاحب الجيم')+part('admin','مدير النظام')+'<p class="app-status-note">تُعرض الصفحات الواردة في الملفات فقط. التسجيل وتسجيل الدخول موجودان في مشروع Laravel الأصلي وغير مضمنين في هذه المعاينة.</p>');
    }
    let mapInstance=null,positionMarker=null,pickerMap=null,pickerCoords=null;
    const initMap=()=>{
      const element=document.getElementById('optiMap'); if(!element)return;
      if(typeof window.L==='undefined'){element.innerHTML='<div class="app-map-fallback">الخريطة التفاعلية تحتاج اتصالاً بالإنترنت لتحميل مكتبة الخرائط.</div>';return;}
      if(mapInstance){mapInstance.remove();mapInstance=null;}
      element.innerHTML='';mapInstance=L.map(element).setView([31.95,35.92],11);
      L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap contributors',maxZoom:19}).addTo(mapInstance);
      const bounds=[];
      availableGyms().forEach(g=>{
        const coords=safeCoords(g);if(!coords)return;
        bounds.push(coords);
        const pop=document.createElement('div');pop.style.textAlign='right';
        const title=document.createElement('strong');title.textContent=g.name;pop.append(title,document.createElement('br'));
        const label=document.createElement('span');label.textContent=g.area+' — '+gymPlan(g)+' د.أ';pop.append(label,document.createElement('br'));
        const btn=document.createElement('button');btn.textContent='عرض التفاصيل';btn.style.cursor='pointer';btn.onclick=()=>{app.selectedGym=g.id;save();state.view='details';render();};pop.append(btn);
        L.marker(coords).addTo(mapInstance).bindPopup(pop);
      });
      if(bounds.length)mapInstance.fitBounds(bounds,{padding:[40,40],maxZoom:13});
      setTimeout(()=>mapInstance?.invalidateSize(),100);
    };
    const openPicker=()=>{
      const current=currentOwnerGym();if(!current)return toast('أضف ناديًا أولاً.');
      modal('تحديد موقع النادي على الخريطة',`<div id="pickerMap" class="app-map"></div><p class="app-status-note">اضغط على المكان المطلوب على الخريطة، ثم احفظ الإحداثيات.</p><div class="app-form-grid">${inputField('خط العرض','pickLat',gymExtra(current.id).latitude,'number','step="any"')}${inputField('خط الطول','pickLng',gymExtra(current.id).longitude,'number','step="any"')}</div><div class="modal-footer"><button class="primary" data-app="pick-save">${icon('check')} استخدام الموقع</button><button class="small-button" data-action="close">إلغاء</button></div>`);
      if(window.L){setTimeout(()=>{if(pickerMap){pickerMap.remove();pickerMap=null;}const coords=safeCoords(current)||[31.95,35.92];pickerMap=L.map('pickerMap').setView(coords,12);L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png',{attribution:'&copy; OpenStreetMap contributors'}).addTo(pickerMap);let mark=L.marker(coords).addTo(pickerMap);pickerMap.on('click',ev=>{pickerCoords=[ev.latlng.lat,ev.latlng.lng];mark.setLatLng(pickerCoords);document.querySelector('#fld-pickLat').value=pickerCoords[0].toFixed(7);document.querySelector('#fld-pickLng').value=pickerCoords[1].toFixed(7);});pickerMap.invalidateSize();},80);}
      else document.getElementById('pickerMap').innerHTML='<div class="app-map-fallback">الخريطة تحتاج اتصالاً بالإنترنت. يمكن كتابة الإحداثيات في الحقول.</div>';
    };
    const actionApp=(b)=>{
      const act=b.dataset.app,id=Number(b.dataset.id),g=db.gyms.find(x=>x.id===id);
      if(act==='gym-detail'&&g){app.selectedGym=g.id;save();state.view='details';render();}
      else if(act==='favorite'&&g){app.favorites=app.favorites.includes(id)?app.favorites.filter(x=>x!==id):[...app.favorites,id];save();render();toast('تم تحديث النوادي المفضلة.');}
      else if(act==='compare'&&g){app.compare=app.compare.includes(id)?app.compare.filter(x=>x!==id):[...app.compare,id];save();render();toast('تم تحديث قائمة المقارنة.');}
      else if(act==='remove-time'){app.times.splice(id,1);save();render();}
      else if(act==='review-delete'){if(!confirm('حذف تقييمك؟'))return;app.reviews.splice(id,1);save();render();toast('تم حذف التقييم محليًا.');}
      else if(act==='review-edit'){
        const r=app.reviews[id];if(!r)return;
        modal('تعديل التقييم',`<form data-app-form="edit-review"><input type="hidden" name="reviewIndex" value="${id}">${selectField('التقييم','rating',[[5,'5 نجوم'],[4,'4 نجوم'],[3,'3 نجوم'],[2,'نجمتان'],[1,'نجمة']],r.rating)}<div class="field"><label for="reviewEditText">التعليق</label><textarea id="reviewEditText" name="comment" required>${esc(r.comment)}</textarea></div>${footerAction()}</form>`);
      }
      else if(act==='owner-new'){if(LIVE){toast('إضافة النادي من لوحة المالك تحتاج مسار حفظ مستقل، ولم يتم تفعيله بعد.');return;}
        const id=Math.max(100,...db.gyms.map(g=>g.id))+1;
        db.gyms.push({id,name:'نادي جديد',owner:'صاحب الجيم التجريبي',area:'',date:new Date().toISOString().slice(0,10),complete:0,status:'pending',price:0,phone:'',notes:''});
        app.ownerGymIds.push(id);app.selectedOwnerGym=id;gymExtra(id);save();state.view='edit';render();toast('أضيف نادي جديد بحالة قيد المراجعة. أدخل بياناته.');
      }
      else if(act==='owner-select'&&g&&app.ownerGymIds.includes(id)){app.selectedOwnerGym=id;state.view='edit';save();render();}
      else if(act==='owner-delete'&&g&&app.ownerGymIds.includes(id)){
        if(!confirm('هل تريد حذف النادي من بيانات المعاينة؟'))return;
        app.ownerGymIds=app.ownerGymIds.filter(x=>x!==id);
        db.gyms=db.gyms.filter(x=>x.id!==id);
        delete app.ownerExtras[id];app.selectedOwnerGym=app.ownerGymIds[0]||null;
        save();render();toast('تم حذف النادي من المعاينة المحلية.');
      }
      else if(act==='membership-delete'&&currentOwnerGym()){
        if(!confirm('حذف هذه الخطة؟'))return;gymExtra(currentOwnerGym().id).memberships.splice(id,1);save();render();
      }
      else if(act==='membership-edit'&&currentOwnerGym()){
        const m=gymExtra(currentOwnerGym().id).memberships[id];if(!m)return;
        modal('تعديل الاشتراك',`<form data-app-form="membership-edit"><input type="hidden" name="index" value="${id}">${inputField('المدة بالأشهر','duration',m.duration,'number','min="1" required')}${inputField('السعر بالدينار','price',m.price,'number','min="0" step="0.01" required')}${footerAction()}</form>`);
      }
      else if(act==='hours-delete'&&currentOwnerGym()){gymExtra(currentOwnerGym().id).hours.splice(id,1);save();render();}
      else if(act==='photo-delete'&&currentOwnerGym()){
        if(!confirm('حذف هذه الصورة؟'))return;gymExtra(currentOwnerGym().id).images.splice(id,1);save();render();
      }
      else if(act==='map-location')openPicker();
      else if(act==='pick-save'){
        const lat=Number(document.getElementById('fld-pickLat').value),lng=Number(document.getElementById('fld-pickLng').value);
        if(!Number.isFinite(lat)||!Number.isFinite(lng)||!document.getElementById('fld-pickLat').value||!document.getElementById('fld-pickLng').value||lat< -90||lat>90||lng< -180||lng>180)return toast('أدخل إحداثيات صحيحة.');
        const gy=currentOwnerGym();if(!gy)return;
        const x=gymExtra(gy.id);x.latitude=lat;x.longitude=lng;save();close();render();toast('تم تحديد موقع النادي محليًا.');
      }
      else if(act==='geolocate'){
        if(!navigator.geolocation)return toast('المتصفح لا يدعم تحديد الموقع.');
        navigator.geolocation.getCurrentPosition(pos=>{
          if(!mapInstance)return;
          const coords=[pos.coords.latitude,pos.coords.longitude];if(positionMarker)positionMarker.remove();
          positionMarker=L.marker(coords).addTo(mapInstance).bindPopup('موقعي الحالي');mapInstance.setView(coords,13);
        },()=>toast('تعذر الوصول للموقع. تأكد من إذن الموقع واتصال HTTPS.'));
      }
      else if(act==='new-chat'){sessionChats[state.role]=[];render();}
    };
    const appFormSubmit=(form)=>{
      const type=form.dataset.appForm,f=new FormData(form),val=k=>String(f.get(k)||'').trim(),choices=()=>f.getAll('choices').map(String);
      if(type==='preference'){
        if(!val('location'))return toast('أدخل موقعك أو منطقتك.');
        app.preference={location:val('location'),gender:val('gender'),height:val('height'),weight:val('weight'),fitnessGoal:val('fitnessGoal'),maxBudget:val('maxBudget')};state.view='activities';
      }
      else if(type==='activities'){app.activities=choices();state.view='facilities';}
      else if(type==='facilities'){app.facilities=choices();state.view='times';}
      else if(type==='add-time'){
        if(val('start')>=val('end'))return toast('وقت النهاية يجب أن يكون بعد وقت البداية.');
        app.times.push({day:val('day'),start:val('start'),end:val('end')});
      }
      else if(type==='gym-search'){
        app.search={location:val('location'),budget:val('budget'),activity:val('activity'),facility:val('facility'),hours:val('hours')};state.view='results';
      }
      else if(type==='review'||type==='edit-review'){
        if(!val('comment'))return toast('اكتب تعليقك.');
        const r={gymId:Number(val('gymId')),rating:Number(val('rating')),comment:val('comment').slice(0,700),date:new Date().toISOString().slice(0,10)};
        if(type==='edit-review'){
          const i=Number(val('reviewIndex'));if(!app.reviews[i])return;
          app.reviews[i].rating=r.rating;app.reviews[i].comment=r.comment;
          close();
        }else{
          const i=app.reviews.findIndex(x=>x.gymId===r.gymId);
          if(i>=0)app.reviews[i]=r;else app.reviews.push(r);
        }
      }
      else if(type==='account'){
        app.account.fullName=val('fullName');app.account.email=val('email');
        if(val('password'))toast('الاسم والبريد حُفظا محليًا؛ تغيير كلمة المرور يحتاج Laravel.');
      }
      else if(type==='notifsettings'){
        if(val('start')&&val('end')&&val('start')>val('end'))return toast('تاريخ النهاية يسبق البداية.');
        app.notificationSettings={enabled:f.has('enabled'),time:val('time'),repeat:val('repeat'),start:val('start'),end:val('end')};
      }
      else if(type==='owner-edit'){
        const g=currentOwnerGym();if(!g)return;
        if(!val('gym_name'))return toast('اسم النادي مطلوب.');
        const x=gymExtra(g.id);
        g.name=val('gym_name');g.area=val('area');g.phone=val('phone');
        ['description','city','address','latitude','longitude','website_url'].forEach(k=>x[k]=val(k));
        g.complete=Math.min(100,Math.round(['gym_name','area','phone','city','address','description','latitude','longitude','website_url'].filter(k=>k==='gym_name'?g.name:k==='area'?g.area:k==='phone'?g.phone:x[k]).length/9*100));
        state.view='mygyms';
      }
      else if(type==='owner-facilities'||type==='owner-activities'){
        const g=currentOwnerGym();if(!g)return;
        gymExtra(g.id)[type==='owner-facilities'?'facilities':'activities']=choices();
      }
      else if(type==='membership'||type==='membership-edit'){
        const g=currentOwnerGym();if(!g)return;
        const duration=Number(val('duration')),price=Number(val('price'));
        if(!Number.isInteger(duration)||duration<1||!Number.isFinite(price)||price<0)return toast('تحقق من مدة وسعر الاشتراك.');
        const list=gymExtra(g.id).memberships;
        if(type==='membership'){list.push({duration,price});if(duration===1)g.price=price;}
        else{const i=Number(val('index'));if(!list[i])return;list[i]={duration,price};if(duration===1)g.price=price;close();}
      }
      else if(type==='hours'){
        if(val('open')>=val('close'))return toast('وقت الإغلاق يجب أن يكون بعد الفتح.');
        const g=currentOwnerGym();if(!g)return;
        const arr=gymExtra(g.id).hours,hr={day:val('day'),open:val('open'),close:val('close')},i=arr.findIndex(x=>x.day===hr.day);
        if(i<0)arr.push(hr);else arr[i]=hr;
      }
      else return;
      save();render();if(type!=='preference'&&type!=='activities'&&type!=='facilities')toast('تم حفظ التغييرات في نسخة المعاينة المحلية.');
    };
    async function appChatSubmit(form){
      const input=form.querySelector('input[name="message"]'),msg=input.value.trim(),role=state.role;
      if(!msg)return;
      sessionChats[role].push({role:'user',text:msg});
      if(location.protocol==='file:'){
        sessionChats[role].push({role:'assistant',text:'هذه معاينة HTML محلية. لتفعيل الردود، اربط الواجهة بخدمة AI على Laravel عبر '+chatEndpoint[role]+'.'});
        render();return;
      }
      const endpoint=chatEndpoint[role];
      try{
        const csrf=document.querySelector('meta[name="csrf-token"]')?.content;
        const resp=await fetch(endpoint,{method:'POST',credentials:'same-origin',headers:{'Content-Type':'application/json','Accept':'application/json',...(csrf?{'X-CSRF-TOKEN':csrf}:{})},body:JSON.stringify({message:msg})});
        if(!resp.ok)throw Error('HTTP '+resp.status);
        const result=await resp.json();
        const message=typeof result.reply==='string'?result.reply:typeof result.response==='string'?result.response:typeof result.message==='string'?result.message:null;
        if(!message)throw Error('صيغة الاستجابة غير معروفة');
        sessionChats[role].push({role:'assistant',text:message});
      }catch(error){sessionChats[role].push({role:'assistant',text:'تعذّر الاتصال بخدمة AI الخاصة بهذا القسم. تأكد من تشغيل المسار '+endpoint+' والتحقق من الصلاحيات. ('+error.message+')'});}
      if(state.role===role&&state.view==='ai')render();
    }

    function render(){
      document.querySelectorAll('[data-role]').forEach(b=>{b.classList.toggle('active',b.dataset.role===state.role);b.setAttribute('aria-pressed',String(b.dataset.role===state.role));});
      fillAppNav();
      main.innerHTML=state.role==='user'?renderUser():state.role==='owner'?renderOwner():({dashboard:renderDashboard,review:renderReview,gyms:renderGyms,users:renderUsers,reports:renderReports,audit:renderAudit,ai:renderAdminAI}[state.view]||renderReview)();
      const title=appNav[state.role].find(([v])=>v===state.view)?.[1]||'OptiFit';
      document.title=title+' | OptiFit';
      if(state.role==='user'&&state.view==='map')setTimeout(initMap,30);
    }
    function showGym(id){ const g=db.gyms.find(x=>x.id===Number(id));if(!g)return;activeGymId=g.id;modal('فحص بيانات الصالة',`<div style="display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap">${badge(g.name,'رقم الطلب #'+g.id)}${statusLabel(g.status)}</div><div class="info-grid">${[['المالك المسؤول',g.owner],['المنطقة',g.area],['تاريخ التقديم',g.date],['سعر الاشتراك الشهري (تجريبي)',g.price+' دينار'],['رقم التواصل',g.phone],['اكتمال البيانات',g.complete+'%']].map(([k,v])=>`<div class="detail"><span class="label">${esc(k)}</span><span class="value">${esc(v)}</span></div>`).join('')}</div><h3 style="font-size:15px">قائمة التحقق الأساسية</h3><div class="checklist">${[['الاسم والموقع',true],['معلومات المالك',true],['خطط الأسعار',g.complete>=80],['صور الصالة والأجهزة',g.complete>=90]].map(([t,ok])=>`<div class="check ${ok?'':'missing'}">${icon(ok?'checkcircle':'alert')}${t}</div>`).join('')}</div><div class="field"><label for="reviewNote">ملاحظات المراجع</label><textarea id="reviewNote" placeholder="أضف ملاحظات ترسل لصاحب الصالة عند طلب التعديلات...">${esc(g.notes)}</textarea></div><p class="hint">في النسخة التجريبية، يمكن تجربة الاعتماد وتعديل حالة الطلب. لا تُرسل رسائل فعلية ولا يتم النشر للعامة.</p><div class="modal-footer">${g.status!=='approved'?`<button class="primary" data-action="approve" type="button">${icon('checkcircle')} اعتماد الصالة</button>`:''}<button class="small-button warning-button" data-action="changes" type="button">${icon('pencil')} طلب تعديلات</button><button class="small-button warning-button" data-action="reject" type="button">${icon('alert')} رفض</button><button class="small-button" data-action="savenote" type="button">حفظ الملاحظات</button><button class="small-button" data-action="close" type="button">إغلاق</button></div>`);}
    function showReport(id){const r=db.reports.find(x=>x.id===Number(id));if(!r)return;activeReportId=r.id;modal('بلاغ رقم #'+r.id,`<div class="info-grid"><div class="detail"><span class="label">الصالة</span><span class="value">${esc(r.gym)}</span></div><div class="detail"><span class="label">تاريخ البلاغ</span><span class="value">${esc(r.date)}</span></div></div><div class="detail"><span class="label">مضمون البلاغ</span><span class="value">${esc(r.title)}</span></div><div class="modal-footer">${r.status==='open'?`<button class="primary" data-action="resolve">${icon('checkcircle')} إغلاق البلاغ كمحلول</button>`:`<button class="small-button" data-action="reopen">${icon('rotate')} إعادة فتح البلاغ</button>`}<button class="small-button" data-action="close">إغلاق النافذة</button></div>`);}
    function showPermissions(){modal('صلاحيات النظام',`<p class="subtitle">إعدادات تمثيلية لتجربة شكل إدارة الأذونات. لا تغيّر صلاحيات على السيرفر.</p><div style="margin-top:15px">${Object.entries(db.permissions).map(([name,on])=>`<div class="permission-row"><span>${esc(name)}</span><button class="toggle ${on?'on':''}" data-permission="${esc(name)}" aria-label="تبديل صلاحية ${esc(name)}" aria-pressed="${on}"></button></div>`).join('')}</div><div class="modal-footer"><button class="primary" data-action="close">${icon('check')} تم</button></div>`);}
    function showCatalog(){showAppCatalog();}
    function showAddGym(){modal('إضافة صالة جديدة',`<form id="newGymForm"><div class="form-grid"><div class="field"><label for="gName">اسم الصالة</label><input id="gName" name="name" required maxlength="80" placeholder="اسم الصالة"/></div><div class="field"><label for="gOwner">المالك المسؤول</label><input id="gOwner" name="owner" required maxlength="80" placeholder="الاسم الكامل"/></div><div class="field"><label for="gArea">المنطقة</label><input id="gArea" name="area" required maxlength="80" placeholder="عبدون، خلدا..."/></div><div class="field"><label for="gPrice">الاشتراك الشهري (دينار)</label><input id="gPrice" name="price" type="number" min="0" max="10000" value="35" required /></div><div class="field"><label for="gPhone">رقم التواصل</label><input id="gPhone" name="phone" maxlength="35" placeholder="079 ..." /></div><div class="field"><label for="gComplete">اكتمال البيانات (%)</label><input id="gComplete" name="complete" type="number" min="0" max="100" value="80" required /></div></div><div class="modal-footer"><button type="submit" class="primary">${icon('plus')} إضافة إلى المراجعة</button><button type="button" class="small-button" data-action="close">إلغاء</button></div></form>`);}
    function exportCsv(rows, filename){const columns=['اسم الصالة','المالك المسؤول','المنطقة','تاريخ التقديم','اكتمال البيانات','الحالة','السعر الشهري'];const quote=(x)=>'"'+String(x??'').replaceAll('"','""')+'"';let lines=[columns,...rows.map(g=>[g.name,g.owner,g.area,g.date,g.complete,statusNames[g.status],g.price])];const content='\ufeff'+lines.map(row=>row.map(quote).join(',')).join('\r\n');downloadText(content,filename,'text/csv;charset=utf-8');}
    function downloadText(content,filename,type){const blob=new Blob([content],{type});const url=URL.createObjectURL(blob);const a=document.createElement('a');a.href=url;a.download=filename;document.body.appendChild(a);a.click();a.remove();setTimeout(()=>URL.revokeObjectURL(url),1000);}
    async function changeGym(status){
      const g=db.gyms.find(x=>x.id===activeGymId);if(!g)return;
      const note=document.getElementById('reviewNote')?.value?.trim() || '';
      if((status==='changes'||status==='rejected')&&!note){toast('اكتب سبب التعديل أو الرفض أولاً.');return;}
      if(LIVE){
        if(LIVE.role!=='admin'){toast('هذه العملية تتطلب صلاحية مدير النظام.');return;}
        try{
          const csrf=document.querySelector('meta[name="csrf-token"]')?.content;
          const response=await fetch('/optifit/api/gyms/'+encodeURIComponent(g.id)+'/review',{
            method:'POST',credentials:'same-origin',headers:{'Accept':'application/json','Content-Type':'application/json','X-CSRF-TOKEN':csrf || ''},
            body:JSON.stringify({status})
          });
          if(!response.ok)throw new Error('HTTP '+response.status);
          const result=await response.json();
          g.status=result.status;
          close();render();toast('تم تحديث حالة النادي في قاعدة البيانات. ملاحظات النص غير محفوظة بعد.');return;
        }catch(e){toast('تعذر حفظ حالة النادي: '+e.message);return;}
      }
      g.notes=note;g.status=status;log('تغيير حالة النادي '+g.name);close();render();toast('تم التعديل في المعاينة المحلية فقط.');
    }
    document.addEventListener('click',event=>{
      const b=event.target.closest('button');if(!b)return;
      if(b.hasAttribute('data-role')){if(LIVE)return; state.role=b.dataset.role;state.view=state.role==='admin'?'review':'dashboard';state.search='';render();return;}
      if(b.hasAttribute('data-view')){state.view=b.dataset.view;state.search='';render();return;}
      if(b.hasAttribute('data-catalog-route')){const [role,view]=b.dataset.catalogRoute.split(':');if(LIVE && role!==LIVE.role)return;if(!appNav[role]?.some(x=>x[0]===view))return;state.role=role;state.view=view;close();render();return;}
      if(b.hasAttribute('data-go')){state.view=b.dataset.go;render();return;}
      if(b.hasAttribute('data-app')){actionApp(b);return;}
      if(b.hasAttribute('data-filter')){state.filter=b.dataset.filter;state.search='';render();return;}
      if(b.hasAttribute('data-review'))return showGym(b.dataset.review);
      if(b.hasAttribute('data-report'))return showReport(b.dataset.report);
      if(b.hasAttribute('data-user')){const u=db.users[Number(b.dataset.user)];if(u)modal('تفاصيل المستخدم',`<div class="info-grid">${[['الاسم',u.name],['البريد الإلكتروني',u.email],['نوع الحساب',u.role],['الحالة',u.active]].map(([k,v])=>`<div class="detail"><span class="label">${esc(k)}</span><strong>${esc(v)}</strong></div>`).join('')}</div><p class="hint">هذه بطاقة عرض تجريبية، بدون تعديل حقيقي على الحساب.</p>`);return;}
      if(b.hasAttribute('data-permission')){const p=b.dataset.permission;db.permissions[p]=!db.permissions[p];save();b.classList.toggle('on',db.permissions[p]);b.setAttribute('aria-pressed',String(db.permissions[p]));toast('تم حفظ التبديل في المتصفح فقط.');return;}
      if(b.hasAttribute('data-catalog-view')){if(LIVE && LIVE.role!=='admin')return;state.role='admin';state.view=b.dataset.catalogView;state.search='';close();render();return;}
      const a=b.dataset.action;if(!a)return;
      if(a==='close'){if(pickerMap){pickerMap.remove();pickerMap=null;}close();}
      else if(a==='permissions')showPermissions();
      else if(a==='catalog')showCatalog();
      else if(a==='addgym'){if(LIVE){toast('إضافة النادي من لوحة المدير غير مفعّلة بعد.');return;}showAddGym();}
      else if(a==='approve')changeGym('approved');
      else if(a==='changes')changeGym('changes');
      else if(a==='reject')changeGym('rejected');
      else if(a==='savenote'){const g=db.gyms.find(x=>x.id===activeGymId);if(g){g.notes=document.getElementById('reviewNote').value.trim();log('حفظ ملاحظات صالة «'+g.name+'»');toast('تم حفظ الملاحظات محليًا.');}}
      else if(a==='resolve'||a==='reopen'){const r=db.reports.find(x=>x.id===activeReportId);if(r){r.status=a==='resolve'?'done':'open';log((a==='resolve'?'إغلاق':'إعادة فتح')+' البلاغ #'+r.id);close();render();toast('تم تحديث حالة البلاغ.');}}
      else if(a==='export'){exportCsv(listGyms(),'OptiFit-gyms-demo.csv');toast('تم تصدير النتائج الظاهرة إلى CSV.');}
      else if(a==='exportlog'){downloadText('\ufeff'+db.logs.map(v=>`${datestr(v.at)} — ${v.message}`).join('\r\n'),'OptiFit-audit-demo.txt','text/plain;charset=utf-8');toast('تم تصدير السجل.');}
      else if(a==='reset'){if(LIVE){toast('إعادة بيانات النظام محليًا غير متاحة في وضع Laravel.');return;} if(confirm('هل تريد إعادة كل بيانات التجربة إلى حالتها الأولى؟')){db=defaults();db.app=appDefault();app=db.app;save();close();render();toast('تمت إعادة البيانات التجريبية.');}}
    });
    document.addEventListener('input',event=>{
      if(event.target.id!=='searchInput')return;
      state.search=event.target.value;
      const rows=listGyms();document.getElementById('gymBody').innerHTML=gymRows(rows);
      document.getElementById('resultCount').textContent=rows.length;
      document.getElementById('emptyGym').style.display=rows.length?'none':'block';
    });
    document.addEventListener('submit',event=>{
      if(event.target.dataset.appForm){event.preventDefault();if(event.target.dataset.appForm==='chat'){appChatSubmit(event.target);}else appFormSubmit(event.target);return;}
      if(event.target.id!=='newGymForm')return;event.preventDefault();if(LIVE){toast('الإضافة من هذه النافذة غير متاحة في الوضع المتصل.');return;}const form=new FormData(event.target);const name=String(form.get('name')||'').trim();const owner=String(form.get('owner')||'').trim();const area=String(form.get('area')||'').trim();if(!name||!owner||!area){toast('أكمل الحقول الأساسية.');return;}
      const price=Number(form.get('price')),complete=Number(form.get('complete'));
      if(!Number.isFinite(price)||price<0||!Number.isFinite(complete)||complete<0||complete>100){toast('تأكد من صحة الأرقام المدخلة.');return;}
      const id=Math.max(...db.gyms.map(g=>g.id),100)+1;
      db.gyms.unshift({id,name,owner,area,date:new Date().toISOString().slice(0,10),complete,status:'pending',price,phone:String(form.get('phone')||''),notes:''});
      log('إضافة صالة جديدة «'+name+'» إلى طابور المراجعة');state.view='review';state.filter='pending';state.search='';close();render();toast('تمت إضافة الصالة إلى طابور المراجعة.');
    });
    document.addEventListener('change',async event=>{
      const field=event.target;
      if(field.dataset.appSelect==='owner-gym'){const id=Number(field.value);if(app.ownerGymIds.includes(id)){app.selectedOwnerGym=id;save();render();}}
      if(field.dataset.appCheck==='compare'){const id=Number(field.value);app.compare=field.checked?[...new Set([...app.compare,id])]:app.compare.filter(x=>x!==id);save();render();}
      if(field.name==='ratingFilter'){state.ownerRating=field.value;render();}
      if(field.id==='gymUpload'){
        const gym=currentOwnerGym();if(!gym)return;
        const images=gymExtra(gym.id).images;
        for(const file of [...field.files]){
          if(images.length>=10){toast('الحد الأقصى 10 صور للنادي.');break;}
          if(!['image/jpeg','image/png','image/webp'].includes(file.type)||file.size>500*1024){toast('يمكن رفع صور JPG أو PNG أو WebP بحجم أقل من 500KB في المعاينة.');continue;}
          try {const data=await new Promise((res,rej)=>{const r=new FileReader();r.onload=()=>res(r.result);r.onerror=rej;r.readAsDataURL(file)});images.push(data);}catch(e){toast('تعذر قراءة إحدى الصور.');}
        }
        save();render();
      }
    });
    backdrop.addEventListener('click',e=>{if(e.target===backdrop)close();});
    document.addEventListener('keydown',e=>{if(e.key==='Escape'&&backdrop.classList.contains('open'))close();});
    if(state.role==='user' && state.view==='map') setTimeout(initMap,40);
