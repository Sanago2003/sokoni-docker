document.addEventListener('DOMContentLoaded',()=>{
  document.querySelectorAll('form[data-confirm]').forEach(f=>f.addEventListener('submit',e=>{if(!confirm(f.dataset.confirm))e.preventDefault();}));
  const region=document.querySelector('select[name="region_id"]');
  const district=document.querySelector('select[name="district_id"]');
  const ward=document.querySelector('select[name="ward_id"]');
  const village=document.querySelector('select[name="village_id"]');
  async function load(select,type,id,placeholder){if(!select)return;select.innerHTML=`<option value="">${placeholder}</option>`;if(!id)return;try{const r=await fetch(`api/locations.php?type=${type}&id=${encodeURIComponent(id)}`);const j=await r.json();(j.data||[]).forEach(x=>{const o=document.createElement('option');o.value=x.id;o.textContent=x.name;select.appendChild(o);});}catch(e){console.warn('Location lookup failed',e);}}
  if(region&&district&&location.pathname.includes('post.php')||region&&district&&document.querySelector('section.auth-shell')){region.addEventListener('change',()=>{load(district,'districts',region.value,'Choose district');if(ward)ward.innerHTML='<option value="">Choose ward</option>';if(village)village.innerHTML='<option value="">Choose village</option>';});}
  if(district&&ward){district.addEventListener('change',()=>{load(ward,'wards',district.value,'Choose ward');if(village)village.innerHTML='<option value="">Choose village</option>';});}
  if(ward&&village){ward.addEventListener('change',()=>load(village,'villages',ward.value,'Choose village'));}
});
