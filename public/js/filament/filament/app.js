(()=>{var Z=Object.create,L=Object.defineProperty,ee=Object.getPrototypeOf,te=Object.prototype.hasOwnProperty,re=Object.getOwnPropertyNames,ne=Object.getOwnPropertyDescriptor,ae=o=>L(o,"__esModule",{value:!0}),ie=(o,n)=>()=>(n||(n={exports:{}},o(n.exports,n)),n.exports),se=(o,n,p)=>{if(n&&typeof n=="object"||typeof n=="function")for(let d of re(n))!te.call(o,d)&&d!=="default"&&L(o,d,{get:()=>n[d],enumerable:!(p=ne(n,d))||p.enumerable});return o},oe=o=>se(ae(L(o!=null?Z(ee(o)):{},"default",o&&o.__esModule&&"default"in o?{get:()=>o.default,enumerable:!0}:{value:o,enumerable:!0})),o),fe=ie((o,n)=>{(function(p,d,P){if(!p)return;for(var h={8:"backspace",9:"tab",13:"enter",16:"shift",17:"ctrl",18:"alt",20:"capslock",27:"esc",32:"space",33:"pageup",34:"pagedown",35:"end",36:"home",37:"left",38:"up",39:"right",40:"down",45:"ins",46:"del",91:"meta",93:"meta",224:"meta"},y={106:"*",107:"+",109:"-",110:".",111:"/",186:";",187:"=",188:",",189:"-",190:".",191:"/",192:"`",219:"[",220:"\\",221:"]",222:"'"},g={"~":"`","!":"1","@":"2","#":"3",$:"4","%":"5","^":"6","&":"7","*":"8","(":"9",")":"0",_:"-","+":"=",":":";",'"':"'","<":",",">":".","?":"/","|":"\\"},q={option:"alt",command:"meta",return:"enter",escape:"esc",plus:"+",mod:/Mac|iPod|iPhone|iPad/.test(navigator.platform)?"meta":"ctrl"},S,w=1;w<20;++w)h[111+w]="f"+w;for(w=0;w<=9;++w)h[w+96]=w.toString();function O(e,t,a){if(e.addEventListener){e.addEventListener(t,a,!1);return}e.attachEvent("on"+t,a)}function T(e){if(e.type=="keypress"){var t=String.fromCharCode(e.which);return e.shiftKey||(t=t.toLowerCase()),t}return h[e.which]?h[e.which]:y[e.which]?y[e.which]:String.fromCharCode(e.which).toLowerCase()}function $(e,t){return e.sort().join(",")===t.sort().join(",")}function B(e){var t=[];return e.shiftKey&&t.push("shift"),e.altKey&&t.push("alt"),e.ctrlKey&&t.push("ctrl"),e.metaKey&&t.push("meta"),t}function V(e){if(e.preventDefault){e.preventDefault();return}e.returnValue=!1}function H(e){if(e.stopPropagation){e.stopPropagation();return}e.cancelBubble=!0}function C(e){return e=="shift"||e=="ctrl"||e=="alt"||e=="meta"}function J(){if(!S){S={};for(var e in h)e>95&&e<112||h.hasOwnProperty(e)&&(S[h[e]]=e)}return S}function U(e,t,a){return a||(a=J()[e]?"keydown":"keypress"),a=="keypress"&&t.length&&(a="keydown"),a}function X(e){return e==="+"?["+"]:(e=e.replace(/\+{2}/g,"+plus"),e.split("+"))}function I(e,t){var a,c,b,M=[];for(a=X(e),b=0;b<a.length;++b)c=a[b],q[c]&&(c=q[c]),t&&t!="keypress"&&g[c]&&(c=g[c],M.push("shift")),C(c)&&M.push(c);return t=U(c,M,t),{key:c,modifiers:M,action:t}}function D(e,t){return e===null||e===d?!1:e===t?!0:D(e.parentNode,t)}function v(e){var t=this;if(e=e||d,!(t instanceof v))return new v(e);t.target=e,t._callbacks={},t._directMap={};var a={},c,b=!1,M=!1,E=!1;function K(r){r=r||{};var s=!1,l;for(l in a){if(r[l]){s=!0;continue}a[l]=0}s||(E=!1)}function j(r,s,l,i,u,m){var f,_,A=[],k=l.type;if(!t._callbacks[r])return[];for(k=="keyup"&&C(r)&&(s=[r]),f=0;f<t._callbacks[r].length;++f)if(_=t._callbacks[r][f],!(!i&&_.seq&&a[_.seq]!=_.level)&&k==_.action&&(k=="keypress"&&!l.metaKey&&!l.ctrlKey||$(s,_.modifiers))){var Q=!i&&_.combo==u,W=i&&_.seq==i&&_.level==m;(Q||W)&&t._callbacks[r].splice(f,1),A.push(_)}return A}function x(r,s,l,i){t.stopCallback(s,s.target||s.srcElement,l,i)||r(s,l)===!1&&(V(s),H(s))}t._handleKey=function(r,s,l){var i=j(r,s,l),u,m={},f=0,_=!1;for(u=0;u<i.length;++u)i[u].seq&&(f=Math.max(f,i[u].level));for(u=0;u<i.length;++u){if(i[u].seq){if(i[u].level!=f)continue;_=!0,m[i[u].seq]=1,x(i[u].callback,l,i[u].combo,i[u].seq);continue}_||x(i[u].callback,l,i[u].combo)}var A=l.type=="keypress"&&M;l.type==E&&!C(r)&&!A&&K(m),M=_&&l.type=="keydown"};function G(r){typeof r.which!="number"&&(r.which=r.keyCode);var s=T(r);if(s){if(r.type=="keyup"&&b===s){b=!1;return}t.handleKey(s,B(r),r)}}function Y(){clearTimeout(c),c=setTimeout(K,1e3)}function z(r,s,l,i){a[r]=0;function u(k){return function(){E=k,++a[r],Y()}}function m(k){x(l,k,r),i!=="keyup"&&(b=T(k)),setTimeout(K,10)}for(var f=0;f<s.length;++f){var _=f+1===s.length,A=_?m:u(i||I(s[f+1]).action);N(s[f],A,i,r,f)}}function N(r,s,l,i,u){t._directMap[r+":"+l]=s,r=r.replace(/\s+/g," ");var m=r.split(" "),f;if(m.length>1){z(r,m,s,l);return}f=I(r,l),t._callbacks[f.key]=t._callbacks[f.key]||[],j(f.key,f.modifiers,{type:f.action},i,r,u),t._callbacks[f.key][i?"unshift":"push"]({callback:s,modifiers:f.modifiers,action:f.action,seq:i,level:u,combo:r})}t._bindMultiple=function(r,s,l){for(var i=0;i<r.length;++i)N(r[i],s,l)},O(e,"keypress",G),O(e,"keydown",G),O(e,"keyup",G)}v.prototype.bind=function(e,t,a){var c=this;return e=e instanceof Array?e:[e],c._bindMultiple.call(c,e,t,a),c},v.prototype.unbind=function(e,t){var a=this;return a.bind.call(a,e,function(){},t)},v.prototype.trigger=function(e,t){var a=this;return a._directMap[e+":"+t]&&a._directMap[e+":"+t]({},e),a},v.prototype.reset=function(){var e=this;return e._callbacks={},e._directMap={},e},v.prototype.stopCallback=function(e,t){var a=this;if((" "+t.className+" ").indexOf(" mousetrap ")>-1||D(t,a.target))return!1;if("composedPath"in e&&typeof e.composedPath=="function"){var c=e.composedPath()[0];c!==e.target&&(t=c)}return t.tagName=="INPUT"||t.tagName=="SELECT"||t.tagName=="TEXTAREA"||t.isContentEditable},v.prototype.handleKey=function(){var e=this;return e._handleKey.apply(e,arguments)},v.addKeycodes=function(e){for(var t in e)e.hasOwnProperty(t)&&(h[t]=e[t]);S=null},v.init=function(){var e=v(d);for(var t in e)t.charAt(0)!=="_"&&(v[t]=function(a){return function(){return e[a].apply(e,arguments)}}(t))},v.init(),p.Mousetrap=v,typeof n<"u"&&n.exports&&(n.exports=v),typeof define=="function"&&define.amd&&define(function(){return v})})(typeof window<"u"?window:null,typeof window<"u"?document:null)}),R=oe(fe());(function(o){if(o){var n={},p=o.prototype.stopCallback;o.prototype.stopCallback=function(d,P,h,y){var g=this;return g.paused?!0:n[h]||n[y]?!1:p.call(g,d,P,h)},o.prototype.bindGlobal=function(d,P,h){var y=this;if(y.bind(d,P,h),d instanceof Array){for(var g=0;g<d.length;g++)n[d[g]]=!0;return}n[d]=!0},o.init()}})(typeof Mousetrap<"u"?Mousetrap:void 0);var le=o=>{o.directive("mousetrap",(n,{modifiers:p,expression:d},{evaluate:P})=>{let h=()=>d?P(d):n.click();p=p.map(y=>y.replace("-","+")),p.includes("global")&&(p=p.filter(y=>y!=="global"),R.default.bindGlobal(p,y=>{y.preventDefault(),h()})),R.default.bind(p,y=>{y.preventDefault(),h()})})},F=le;document.addEventListener("alpine:init",()=>{window.Alpine.plugin(F),window.Alpine.store("sidebar",{isOpen:window.Alpine.$persist(!0).as("isOpen"),collapsedGroups:window.Alpine.$persist(null).as("collapsedGroups"),groupIsCollapsed:function(n){return this.collapsedGroups.includes(n)},collapseGroup:function(n){this.collapsedGroups.includes(n)||(this.collapsedGroups=this.collapsedGroups.concat(n))},toggleCollapsedGroup:function(n){this.collapsedGroups=this.collapsedGroups.includes(n)?this.collapsedGroups.filter(p=>p!==n):this.collapsedGroups.concat(n)},close:function(){this.isOpen=!1},open:function(){this.isOpen=!0}});let o=localStorage.getItem("theme")??"system";window.Alpine.store("theme",o==="dark"||o==="system"&&window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light"),window.addEventListener("theme-changed",n=>{let p=n.detail;localStorage.setItem("theme",p),p==="system"&&(p=window.matchMedia("(prefers-color-scheme: dark)").matches?"dark":"light"),window.Alpine.store("theme",p)}),window.matchMedia("(prefers-color-scheme: dark)").addEventListener("change",n=>{localStorage.getItem("theme")==="system"&&window.Alpine.store("theme",n.matches?"dark":"light")}),window.Alpine.effect(()=>{window.Alpine.store("theme")==="dark"?document.documentElement.classList.add("dark"):document.documentElement.classList.remove("dark")})});})();

//sidebar guruhlarini ochish va yopish, faqat bir guruhni ochish,Alpine.js bilan sinxronlashtirish
document.addEventListener('DOMContentLoaded', () => {
    console.log('app.js successfully loaded in Filament!');

    // Alpine.js yuklanishini kutish
    document.addEventListener('alpine:init', () => {
        const navGroups = document.querySelectorAll('.fi-sidebar-group');

        navGroups.forEach(currentGroup => {
            const toggleButton = currentGroup.querySelector('.fi-sidebar-group-collapse-button');
            const groupButton = currentGroup.querySelector('.fi-sidebar-group-button');
            const groupLabel = currentGroup.dataset.groupLabel;

            if (toggleButton && groupLabel) {
                // Dastlabki holatni sinxronlashtirish
                Alpine.nextTick(() => {
                    const isCollapsed = Alpine.store('sidebar').groupIsCollapsed(groupLabel);
                    toggleButton.setAttribute('aria-expanded', isCollapsed ? 'false' : 'true');
                    toggleButton.classList.toggle('-rotate-180', isCollapsed);
                });

                // <button> uchun hodisa
                toggleButton.addEventListener('click', () => {
                    Alpine.nextTick(() => {
                        const isExpanding = Alpine.store('sidebar').groupIsCollapsed(groupLabel);
                        Alpine.store('sidebar').toggleCollapsedGroup(groupLabel);

                        toggleButton.setAttribute('aria-expanded', isExpanding ? 'true' : 'false');
                        toggleButton.classList.toggle('-rotate-180', !isExpanding);

                        // Boshqa guruhlarni yopish
                        if (isExpanding) {
                            navGroups.forEach(otherGroup => {
                                const otherLabel = otherGroup.dataset.groupLabel;
                                if (otherLabel !== groupLabel && !Alpine.store('sidebar').groupIsCollapsed(otherLabel)) {
                                    Alpine.store('sidebar').toggleCollapsedGroup(otherLabel);

                                    const otherButton = otherGroup.querySelector('.fi-sidebar-group-collapse-button');
                                    if (otherButton) {
                                        otherButton.setAttribute('aria-expanded', 'false');
                                        otherButton.classList.add('-rotate-180');
                                    }
                                }
                            });
                        }
                    });
                });

                // <div> uchun hodisa
                if (groupButton) {
                    groupButton.addEventListener('click', () => {
                        Alpine.nextTick(() => {
                            const isExpanding = Alpine.store('sidebar').groupIsCollapsed(groupLabel);
                            Alpine.store('sidebar').toggleCollapsedGroup(groupLabel);

                            toggleButton.setAttribute('aria-expanded', isExpanding ? 'true' : 'false');
                            toggleButton.classList.toggle('-rotate-180', !isExpanding);

                            // Boshqa guruhlarni yopish
                            if (isExpanding) {
                                navGroups.forEach(otherGroup => {
                                    const otherLabel = otherGroup.dataset.groupLabel;
                                    if (otherLabel !== groupLabel && !Alpine.store('sidebar').groupIsCollapsed(otherLabel)) {
                                        Alpine.store('sidebar').toggleCollapsedGroup(otherLabel);

                                        const otherButton = otherGroup.querySelector('.fi-sidebar-group-collapse-button');
                                        if (otherButton) {
                                            otherButton.setAttribute('aria-expanded', 'false');
                                            otherButton.classList.add('-rotate-180');
                                        }
                                    }
                                });
                            }
                        });
                    });
                }
            }
        });
    });
});

// Ta'lim guruhidagi guruh sahifasida Bitta dars narxini hisoblash
function calculatePricePerLesson() {
    const totalPrice = document.getElementById('totalPrice').value;
    const lessonsCount = document.getElementById('lessonsCount').value;
    const coursePriceInput = document.getElementById('coursePrice');

    if (totalPrice && lessonsCount && lessonsCount > 0) {
        const pricePerLesson = Math.round(totalPrice / lessonsCount);
        coursePriceInput.value = pricePerLesson;
    } else {
        coursePriceInput.value = '';
    }
}

//Tableda saralash,sahifalardagi jadvalllarni ascending va descending qilib saralash
document.addEventListener('alpine:init', () => {
    Alpine.data('sortableTable', () => ({
        sortField: null,
        sortDirection: 'asc',
        
        sort(field) {
            if (this.sortField === field) {
                this.sortDirection = this.sortDirection === 'asc' ? 'desc' : 'asc';
            } else {
                this.sortField = field;
                this.sortDirection = 'asc';
            }
            
            // Sorting logikasi
            this.sortTable();
        },
        
        sortTable() {
            const tbody = document.querySelector('tbody');
            const rows = Array.from(tbody.querySelectorAll('tr'));
            
            rows.sort((a, b) => {
                const aValue = a.querySelector(`td[data-field="${this.sortField}"]`).textContent;
                const bValue = b.querySelector(`td[data-field="${this.sortField}"]`).textContent;
                
                if (this.sortDirection === 'asc') {
                    return aValue.localeCompare(bValue);
                } else {
                    return bValue.localeCompare(aValue);
                }
            });
            
            // Jadval qatorlarini yangilash
            tbody.innerHTML = '';
            rows.forEach(row => tbody.appendChild(row));
        }
    }));
});