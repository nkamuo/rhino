import{_ as d}from"./AddressForm.vue_vue_type_script_setup_true_lang-b8fa29c7.js";import{c as u}from"./address-ff799320.js";import{T as f,ao as p,am as _,an as g,r as h,U as x,W as V,X as e,j as a,$ as o,a0 as y,ad as C,_ as b,Z as v,a1 as k}from"./index-792b7300.js";import{V as w}from"./VRow-0d652b6c.js";import{V as A}from"./VLayout-0dba6cea.js";import{V as B}from"./VMain-603c68fa.js";import"./VCol-6b418aff.js";import"./VAlert-b239340a.js";import"./VDivider-130fdbf6.js";import"./VForm-b32d5c63.js";import"./connection-13f4ed86.js";import"./ssrBoot-10aa12ef.js";const N=k("span",{class:"mx-5 mt-2 text-h5 text-lg-h4"},"Add new Address Book entry",-1),q=f({__name:"CreateAddressPage",props:{},emits:["update:open"],setup(T,{emit:j}){const i=p();_();const l=g(),s=h(!1);async function c(n){try{s.value=!0,await u({input:n}),i.replace({name:"addressing.home"})}catch(t){const r=t.message;l({text:r})}finally{s.value=!1}}function m(){i.back()}return(n,t)=>(x(),V(B,{scrollable:""},{default:e(()=>[a(o,{"min-height":"calc(100vh - 70px)",color:"grey-lighten-4",class:"rounded-0",flat:""},{default:e(()=>[a(w,{justify:"center",align:"center",class:"fill-height",style:{"min-height":"calc(100vh - 70px)"}},{default:e(()=>[a(o,{width:"1000px","min-height":"400px",flat:""},{default:e(()=>[a(y,{class:"pa-1"},{default:e(()=>[a(d,{onSubmit:t[0]||(t[0]=r=>c(r)),onCancel:t[1]||(t[1]=()=>m()),submiting:s.value},{title:e(()=>[a(C,null,{default:e(()=>[a(A,{justify:"space-around"},{default:e(()=>[a(b,{"r-size":"x-large",style:{"font-size":"50px"}},{default:e(()=>[v("mdi-plus-circle")]),_:1}),N]),_:1})]),_:1})]),_:1},8,["submiting"])]),_:1})]),_:1}),a(o,{flat:""})]),_:1})]),_:1})]),_:1}))}});export{q as default};