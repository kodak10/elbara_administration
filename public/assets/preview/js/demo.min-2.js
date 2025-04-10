/*!
 * Tabler Demo v1.0.0 (https://tabler.io)
 * Copyright 2018-2025 The Tabler Authors
 * Copyright 2018-2025 codecalm.net Paweł Kuna
 * Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)
 */
const items={"menu-position":{localStorage:"tablerMenuPosition",default:"top"},"menu-behavior":{localStorage:"tablerMenuBehavior",default:"sticky"},"container-layout":{localStorage:"tablerContainerLayout",default:"boxed"}},config={};for(const[e,t]of Object.entries(items)){const o=localStorage.getItem(t.localStorage);config[e]=o||t.default}const parseUrl=()=>{const e=window.location.search.substring(1).split("&");for(let t=0;t<e.length;t++){const o=e[t].split("="),n=o[0],r=o[1];items[n]&&(localStorage.setItem(items[n].localStorage,r),config[n]=r)}},toggleFormControls=e=>{for(const[t,o]of Object.entries(items)){const o=e.querySelector(`[name="settings-${t}"][value="${config[t]}"]`);o&&(o.checked=!0)}},submitForm=e=>{for(const[t,o]of Object.entries(items)){const n=e.querySelector(`[name="settings-${t}"]:checked`).value;localStorage.setItem(o.localStorage,n),config[t]=n}window.dispatchEvent(new Event("resize")),new bootstrap.Offcanvas(e).hide()};parseUrl();const form=document.querySelector("#offcanvasSettings");form&&(form.addEventListener("submit",(function(e){e.preventDefault(),submitForm(form)})),toggleFormControls(form));
//# sourceMappingURL=demo.min.js.map