(self.webpackJSONPwpmdb=self.webpackJSONPwpmdb||[]).push([[406],{36890:function(e,t,n){"use strict";n.r(t),n.d(t,{default:function(){return S}});var a,i=n(79043),l=n(4665),r=n(12544),s=n.n(r),o=n(62295),d=n(42233),c=n(75338),m=n(70659),u=n.n(m),p=n(19141),f=n.n(p),_=n(40795),b=n(78579),w=n(22633),v=(n(33647),n(76178)),g=n(83135),E=n(98135),h=n(4516),y=n(64533),O=n(66055),N=n(88368),M=n(50166),k=n(27940),P=n(31330),x=n.n(P),D=(n(67041),n(73042));function L(){return L=Object.assign?Object.assign.bind():function(e){for(var t=1;t<arguments.length;t++){var n=arguments[t];for(var a in n)Object.prototype.hasOwnProperty.call(n,a)&&(e[a]=n[a])}return e},L.apply(this,arguments)}var C=function(e){return l.createElement("svg",L({width:16,height:16,viewBox:"0 0 16 16",fill:"none",xmlns:"http://www.w3.org/2000/svg"},e),a||(a=l.createElement("path",{d:"M13.444 2.5H2.556C1.696 2.5 1 3.122 1 3.889v9.722C1 14.378 1.696 15 2.556 15h10.888c.86 0 1.556-.622 1.556-1.389V3.89c0-.767-.696-1.389-1.556-1.389ZM11 1v3M5 1v3M1 8h14",stroke:"#B3B3B3",strokeWidth:2,strokeLinecap:"round",strokeLinejoin:"round"})))},I=(n.p,(0,o.$j)((function(e){return{media_files:e.media_files}}),{updateMFDate:b.K4})((function(e){var t=e.media_files.date,n=(0,l.useState)(!1),a=(0,N.Z)(n,2),i=a[0],r=a[1],s=(0,l.useRef)(null),o=function(e){s.current.contains(e.target)||setTimeout((function(){r(!1)}),50)};(0,l.useEffect)((function(){return i?document.addEventListener("mousedown",o):document.removeEventListener("mousedown",o),function(){document.removeEventListener("mousedown",o)}}),[i]);var c=(0,M.Yz)(i,null,{from:{opacity:0,marginTop:-25},enter:{opacity:1,marginTop:0},leave:{opacity:0,marginTop:-25}});return t&&""!==t||(t=Date.now()),l.createElement("div",{className:"datepicker-wrap",ref:s},l.createElement("div",null,l.createElement("div",{tabIndex:"0",onClick:function(){r(!0)},onKeyPress:function(e){"Enter"===e.key&&r(!0)},className:"media-date"},l.createElement(C,null),l.createElement("span",null,t?f()(t,"mmmm d yyyy @ hh:MM TT"):""))),c.map((function(n){var a=n.item,s=n.key,o=n.props;return a&&l.createElement(x(),{active:i},l.createElement(M.q.div,{key:s,style:o,className:"datepicker-box"},l.createElement(k.xZ,{currentDate:t||Date.now(),tabIndex:"0",onChange:function(t){e.updateMFDate(t)},is12Hour:!0,isInvalidDate:function(e){var t=new Date;return e.setHours(0,0,0,0),t.setHours(0,0,0,0),e>=new Date}}),l.createElement("div",{className:"close-wrap"},l.createElement("button",{onClick:function(){r(!1)},className:"close-picker"},l.createElement(D.r,{"aria-hidden":!0}),l.createElement("span",{className:"screen-reader-text"},(0,d.__)("Close Date Picker","wp-migrate-db"))))))})))}))),T=n(29214),Z=n(29942),F={pull:(0,d.__)("Pull","wp-migrate-db"),push:(0,d.__)("Push","wp-migrate-db"),savefile:(0,d.__)("Export","wp-migrate-db")},j=function(e){var t=e.selected,n=e.labelledby;return l.createElement("div",null,l.createElement("input",{className:"media-option-radio",type:"radio",name:"media-option",checked:t,"aria-labelledby":n,readOnly:!0}))},B=function(e){var t=e.description,n=e.currentOption,a=e.intent,i=e.optionName,r=e.postDescription,s=e.className,c=e.label,m=(0,o.I0)(),u="media-".concat(i);return l.createElement("div",{onClick:function(){!function(t){if(n===t)return null;m(e.setMediaOption(t))}(i)},className:"media-option ".concat(s||"")},l.createElement(j,{labelledby:u,selected:i===n}),l.createElement("div",null,l.createElement("strong",{id:u},(0,d.gB)(c,F[a])),l.createElement("div",{className:"option-description"},t),l.createElement("div",null,l.createElement("b",null,r))),l.createElement("div",{className:"datepicker-container"},"new"===i&&"new"===n&&l.createElement(I,null)))},S=(0,o.$j)((function(e){var t=e.profiles.profile_loading,n=(0,v.O)("panelsOpen",e),a=(0,h.r5)("status",e),i=e.profiles.loaded_profile;return{isLoading:t,panel_info:e.panels,migration:e.migrations,current_migration:e.migrations.current_migration,addons:e.addons,media_files:e.media_files,status:a,panelsOpen:n,loaded_profile:i}}),{toggleMediaFiles:b.$0,setMediaOption:b.Lm,togglePanelsOpen:w.SO,addOpenPanel:w.LX,removeOpenPanel:w.I4,updateMFExcludes:b.rM,updateMFDate:b.K4})((function(e){var t=e.media_files,n=e.panelsOpen,a=e.status,r=e.migration,m=r.current_migration,p=r.local_site,w=m.intent,v=m.twoMultisites,h=m.localSource,N=t.enabled,M=(0,o.I0)(),k="undefined"!==typeof t.available&&!t.available,P=u()(a,{name:"MF_INVALID_DATE"}),x=u()(a,{name:"MF_OPTION_NULL"}),D=(0,o.v9)((function(e){return e.multisite_tools})),L={all:(0,d.__)("%s all media uploads","wp-migrate-db"),new:(0,d.__)("%s media uploads by date","wp-migrate-db"),new_subsequent:(0,d.__)("%s new and updated media uploads","wp-migrate-db")},C={all:D.enabled?function(){var e=h&&"false"===p.is_multisite||!h&&"true"===p.is_multisite;return!v&&e?(0,d.__)("Copies all files to the uploads folder of the subsite","wp-migrate-db"):(0,d.__)("Copies all files from the uploads folder of the subsite","wp-migrate-db")}():(0,d.__)("Copies all files from the uploads folder","wp-migrate-db"),new:(0,d.__)("Copies new and modified files after a specific date","wp-migrate-db"),new_subsequent:(0,d.__)("Copies new and modified files since the last migration","wp-migrate-db")},I=function(e){return!s()(n,"media_files")&&t.option&&t.enabled?l.createElement(l.Fragment,null,(0,d.gB)(L[t.option],F[w])):null},j=n.includes("media_files"),S=!1;N&&!j&&(S=!0);var q=[];return S&&q.push("has-divider"),N&&q.push("enabled"),l.createElement("div",{className:"media-files"},l.createElement(_.Z,{title:(0,d.__)("Media Uploads","wp-migrate-db"),className:q.join(" "),panelName:"media_files",disabled:k,enabled:N,panelSummary:l.createElement(I,(0,i.Z)({disabled:k,labels:L},e)),forceDivider:S,callback:function(t){return(0,E.SF)(t,"media_files",j,N,k,e.addOpenPanel,e.removeOpenPanel,(function(){return M((0,O.m)(y.h6))}))},toggle:(0,b.$0)(),hasInput:!0},l.createElement("div",{className:"media-files-inner-wrap"},l.createElement("div",{className:"media-files-options"},l.createElement(B,{description:C.all,label:L.all,currentOption:t.option,optionName:"all",intent:w,setMediaOption:b.Lm}),!(0,Z.aE)()&&l.createElement(B,{description:C.new_subsequent,label:L.new_subsequent,postDescription:t.last_migration&&""!==t.last_migration?f()(t.last_migration,"(mmmm d yyyy @ hh:MM TT)"):"",currentOption:t.option,optionName:"new_subsequent",intent:w,setMediaOption:b.Lm}),l.createElement(B,{description:C.new,label:L.new,currentOption:t.option,optionName:"new",intent:w,setMediaOption:b.Lm,className:"option-wrap"})),l.createElement("div",{className:"media-files-excludes excludes-wrap"},l.createElement(g.Z,(0,i.Z)({},e,{excludes:t.excludes,excludesUpdater:e.updateMFExcludes,type:"media"})))),P&&l.createElement(T.Z,{type:"danger"},(0,c.ZP)((0,d.__)('The date selected <a href="https://www.youtube.com/watch?v=G3AfIvJBcGo" target="_blank" rel="noopener noreferrer">is in the future</a>, please select a valid date.',"wp-migrate-db"))),x&&l.createElement(T.Z,{type:"danger"},(0,c.ZP)((0,d.__)("Please select a media option above.","wp-migrate-db")))))}))},24654:function(){}}]);