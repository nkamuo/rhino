import{p as I,I as x,bS as W,m as R,a$ as z,b as J,c as P,q as g,aj as j,h as o,i as K,bW as Q,bx as X,aW as i,l as F,j as d,bD as Y,s as T,r as Z,ak as p,x as B,n as ee,o as le,F as te,_ as ae,bH as ne,C as oe,b7 as ue,aN as U,aG as ie,bs as ce,aL as re,H as se,ah as de}from"./index-792b7300.js";const H=Symbol.for("vuetify:selection-control-group"),N=I({color:String,disabled:{type:Boolean,default:null},defaultsTarget:String,error:Boolean,id:String,inline:Boolean,falseIcon:x,trueIcon:x,ripple:{type:Boolean,default:!0},multiple:{type:Boolean,default:null},name:String,readonly:Boolean,modelValue:null,type:String,valueComparator:{type:Function,default:W},...R(),...z(),...J()},"SelectionControlGroup"),ve=I({...N({defaultsTarget:"VSelectionControl"})},"VSelectionControlGroup");P()({name:"VSelectionControlGroup",props:ve(),emits:{"update:modelValue":e=>!0},setup(e,u){let{slots:r}=u;const l=g(e,"modelValue"),a=j(),v=o(()=>e.id||`v-selection-control-group-${a}`),c=o(()=>e.name||v.value),t=new Set;return K(H,{modelValue:l,forceUpdate:()=>{t.forEach(n=>n())},onForceUpdate:n=>{t.add(n),Q(()=>{t.delete(n)})}}),X({[e.defaultsTarget]:{color:i(e,"color"),disabled:i(e,"disabled"),density:i(e,"density"),error:i(e,"error"),inline:i(e,"inline"),modelValue:l,multiple:o(()=>!!e.multiple||e.multiple==null&&Array.isArray(l.value)),name:c,falseIcon:i(e,"falseIcon"),trueIcon:i(e,"trueIcon"),readonly:i(e,"readonly"),ripple:i(e,"ripple"),type:i(e,"type"),valueComparator:i(e,"valueComparator")}}),F(()=>{var n;return d("div",{class:["v-selection-control-group",{"v-selection-control-group--inline":e.inline},e.class],style:e.style,role:e.type==="radio"?"radiogroup":void 0},[(n=r.default)==null?void 0:n.call(r)])}),{}}});const q=I({label:String,trueValue:null,falseValue:null,value:null,...R(),...N()},"VSelectionControl");function fe(e){const u=oe(H,void 0),{densityClasses:r}=ue(e),l=g(e,"modelValue"),a=o(()=>e.trueValue!==void 0?e.trueValue:e.value!==void 0?e.value:!0),v=o(()=>e.falseValue!==void 0?e.falseValue:!1),c=o(()=>!!e.multiple||e.multiple==null&&Array.isArray(l.value)),t=o({get(){const m=u?u.modelValue.value:l.value;return c.value?m.some(s=>e.valueComparator(s,a.value)):e.valueComparator(m,a.value)},set(m){if(e.readonly)return;const s=m?a.value:v.value;let f=s;c.value&&(f=m?[...U(l.value),s]:U(l.value).filter(V=>!e.valueComparator(V,a.value))),u?u.modelValue.value=f:l.value=f}}),{textColorClasses:n,textColorStyles:y}=ie(o(()=>t.value&&!e.error&&!e.disabled?e.color:void 0)),{backgroundColorClasses:C,backgroundColorStyles:k}=ce(o(()=>t.value&&!e.error&&!e.disabled?e.color:void 0)),h=o(()=>t.value?e.trueIcon:e.falseIcon);return{group:u,densityClasses:r,trueValue:a,falseValue:v,model:t,textColorClasses:n,textColorStyles:y,backgroundColorClasses:C,backgroundColorStyles:k,icon:h}}const _=P()({name:"VSelectionControl",directives:{Ripple:Y},inheritAttrs:!1,props:q(),emits:{"update:modelValue":e=>!0},setup(e,u){let{attrs:r,slots:l}=u;const{group:a,densityClasses:v,icon:c,model:t,textColorClasses:n,textColorStyles:y,backgroundColorClasses:C,backgroundColorStyles:k,trueValue:h}=fe(e),m=j(),s=o(()=>e.id||`input-${m}`),f=T(!1),V=T(!1),S=Z();a==null||a.onForceUpdate(()=>{S.value&&(S.value.checked=t.value)});function A(b){f.value=!0,re(b.target,":focus-visible")!==!1&&(V.value=!0)}function G(){f.value=!1,V.value=!1}function E(b){e.readonly&&a&&se(()=>a.forceUpdate()),t.value=b.target.checked}return F(()=>{var $,w;const b=l.label?l.label({label:e.label,props:{for:s.value}}):e.label,[L,M]=p(r),D=d("input",B({ref:S,checked:t.value,disabled:!!(e.readonly||e.disabled),id:s.value,onBlur:G,onFocus:A,onInput:E,"aria-disabled":!!(e.readonly||e.disabled),type:e.type,value:h.value,name:e.name,"aria-checked":e.type==="checkbox"?t.value:void 0},M),null);return d("div",B({class:["v-selection-control",{"v-selection-control--dirty":t.value,"v-selection-control--disabled":e.disabled,"v-selection-control--error":e.error,"v-selection-control--focused":f.value,"v-selection-control--focus-visible":V.value,"v-selection-control--inline":e.inline},v.value,e.class]},L,{style:e.style}),[d("div",{class:["v-selection-control__wrapper",n.value],style:y.value},[($=l.default)==null?void 0:$.call(l,{backgroundColorClasses:C,backgroundColorStyles:k}),ee(d("div",{class:["v-selection-control__input"]},[((w=l.input)==null?void 0:w.call(l,{model:t,textColorClasses:n,textColorStyles:y,backgroundColorClasses:C,backgroundColorStyles:k,inputNode:D,icon:c.value,props:{onFocus:A,onBlur:G,id:s.value}}))??d(te,null,[c.value&&d(ae,{key:"icon",icon:c.value},null),D])]),[[le("ripple"),e.ripple&&[!e.disabled&&!e.readonly,null,["center","circle"]]]])]),b&&d(ne,{for:s.value,clickable:!0,onClick:O=>O.stopPropagation()},{default:()=>[b]})])}),{isFocused:f,input:S}}}),me=I({indeterminate:Boolean,indeterminateIcon:{type:x,default:"$checkboxIndeterminate"},...q({falseIcon:"$checkboxOff",trueIcon:"$checkboxOn"})},"VCheckboxBtn"),be=P()({name:"VCheckboxBtn",props:me(),emits:{"update:modelValue":e=>!0,"update:indeterminate":e=>!0},setup(e,u){let{slots:r}=u;const l=g(e,"indeterminate"),a=g(e,"modelValue");function v(n){l.value&&(l.value=!1)}const c=o(()=>l.value?e.indeterminateIcon:e.falseIcon),t=o(()=>l.value?e.indeterminateIcon:e.trueIcon);return F(()=>{const n=de(_.filterProps(e)[0],["modelValue"]);return d(_,B(n,{modelValue:a.value,"onUpdate:modelValue":[y=>a.value=y,v],class:["v-checkbox-btn",e.class],style:e.style,type:"checkbox",falseIcon:c.value,trueIcon:t.value,"aria-checked":l.value?"mixed":void 0}),r)}),{}}});export{be as V,me as m};
