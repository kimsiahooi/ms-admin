import{c as y}from"./index-C7TB37iK.js";/**
 * @license lucide-vue-next v0.468.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const U=y("MinusIcon",[["path",{d:"M5 12h14",key:"1ays0h"}]]);/**
 * @license lucide-vue-next v0.468.0 - ISC
 *
 * This source code is licensed under the ISC license.
 * See the LICENSE file in the root directory of this source tree.
 */const s=y("PlusIcon",[["path",{d:"M5 12h14",key:"1ays0h"}],["path",{d:"M12 5v14",key:"s699le"}]]),e=[];for(let n=0;n<256;++n)e.push((n+256).toString(16).slice(1));function a(n,t=0){return(e[n[t+0]]+e[n[t+1]]+e[n[t+2]]+e[n[t+3]]+"-"+e[n[t+4]]+e[n[t+5]]+"-"+e[n[t+6]]+e[n[t+7]]+"-"+e[n[t+8]]+e[n[t+9]]+"-"+e[n[t+10]]+e[n[t+11]]+e[n[t+12]]+e[n[t+13]]+e[n[t+14]]+e[n[t+15]]).toLowerCase()}let r;const h=new Uint8Array(16);function m(){if(!r){if(typeof crypto>"u"||!crypto.getRandomValues)throw new Error("crypto.getRandomValues() not supported. See https://github.com/uuidjs/uuid#getrandomvalues-not-supported");r=crypto.getRandomValues.bind(crypto)}return r(h)}const g=typeof crypto<"u"&&crypto.randomUUID&&crypto.randomUUID.bind(crypto),o={randomUUID:g};function l(n,t,u){var i;if(o.randomUUID&&!t&&!n)return o.randomUUID();n=n||{};const d=n.random??((i=n.rng)==null?void 0:i.call(n))??m();if(d.length<16)throw new Error("Random bytes length must be >= 16");if(d[6]=d[6]&15|64,d[8]=d[8]&63|128,t){if(u=u||0,u<0||u+16>t.length)throw new RangeError(`UUID byte range ${u}:${u+15} is out of buffer bounds`);for(let c=0;c<16;++c)t[u+c]=d[c];return t}return a(d)}const I=()=>({uuid:l});export{U as M,s as P,I as u};
