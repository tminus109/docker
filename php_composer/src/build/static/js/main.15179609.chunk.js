(this["webpackJsonp16-login-system"]=this["webpackJsonp16-login-system"]||[]).push([[0],{34:function(e,t,s){},35:function(e,t,s){},60:function(e,t,s){"use strict";s.r(t);var c=s(0),n=s(1),a=s(27),i=s.n(a),r=(s(34),s(35),s(9)),l=s(2),o=s(8),j=s(13),d=s(12),b=s.n(d),h="";var u=b.a.create();function m(){var e=Object(l.g)(),t=Object(n.useState)(!1),s=Object(o.a)(t,2),a=s[0],i=s[1];return a?Object(c.jsx)("div",{className:"center-item",children:Object(c.jsx)("div",{className:"spinner-border text-danger"})}):Object(c.jsx)("div",{className:"container-fluid d-flex justify-content-center h-100 login-container",children:Object(c.jsxs)("div",{className:"card login-card",children:[Object(c.jsx)("div",{className:"card-header login-card-header",children:Object(c.jsx)("h4",{children:"Bejelentkez\xe9s"})}),Object(c.jsxs)("div",{className:"card-body",children:[Object(c.jsxs)("form",{onSubmit:function(t){var s,c;t.preventDefault(),i(!0),(s=t.target.elements.email.value,c=t.target.elements.password.value,b.a.post("/api/login-user",{email:s,password:c},{withCredentials:!0}).then((function(e){h=e.data.accessToken}))).then((function(){i(!1),e.push("/osszes-szallas")})).catch((function(e){alert("Helytelen bejelentkez\xe9si adatok, k\xe9rj\xfck pr\xf3b\xe1ld \xfajra!"),i(!1)}))},className:"mb-5",children:[Object(c.jsx)("div",{className:"input-group form-group",children:Object(c.jsx)("input",{type:"email",name:"email",className:"form-control",placeholder:"Email"})}),Object(c.jsx)("div",{className:"input-group form-group",children:Object(c.jsx)("input",{type:"password",name:"password",className:"form-control",placeholder:"Jelsz\xf3"})}),Object(c.jsx)("div",{className:"form-group",children:Object(c.jsx)("button",{type:"submit",className:"btn float-right btn-warning",children:"K\xfcld\xe9s"})})]}),Object(c.jsx)("p",{className:"text-light mb-1",children:"Email: user@kodbazis.hu"}),Object(c.jsx)("p",{className:"text-light",children:"Jelsz\xf3: teszt"})]})]})})}function x(){var e=Object(l.g)();return Object(c.jsx)("button",{className:"btn btn-danger m-3 float-right",onClick:function(){b.a.post("/api/logout-user",{},{withCredentials:!0}).then((function(e){h=""})).finally((function(){e.push("/")}))},children:"Kijelentkez\xe9s"})}function O(){var e=Object(n.useState)([]),t=Object(o.a)(e,2),s=t[0],a=t[1],i=Object(n.useState)(!1),j=Object(o.a)(i,2),d=j[0],b=j[1],h=Object(l.g)();return Object(n.useEffect)((function(){b(!0),u.get("/api/szallasok").then((function(e){return e.data})).then((function(e){b(!1),a(e)})).catch((function(){b(!1),h.push("/")}))}),[]),d||!s.length?Object(c.jsx)("div",{className:"center-item",children:Object(c.jsx)("div",{className:"spinner-border text-danger"})}):Object(c.jsxs)("div",{children:[Object(c.jsx)(x,{}),Object(c.jsxs)("ul",{className:"list-group w-100",children:[Object(c.jsxs)("div",{className:"row border-bottom p-2 text-dark",children:[Object(c.jsx)("div",{className:"col-xs-12 col-sm-4",children:Object(c.jsx)("h5",{className:"visible-xs",children:"Megnevez\xe9s"})}),Object(c.jsx)("h5",{className:"col-xs-4 col-sm-2 right",children:"Helysz\xedn"}),Object(c.jsx)("h5",{className:"col-xs-8 col-sm-3",children:"Minimum \xe9jszak\xe1k sz\xe1ma"}),Object(c.jsx)("h5",{className:"col-xs-10 col-sm-2",children:"\xc1r"})]}),s.map((function(e){return Object(c.jsx)(r.b,{to:"/szallas-"+e.id,children:Object(c.jsxs)("div",{className:"row border-bottom p-2 text-dark",children:[Object(c.jsxs)("div",{className:"col-xs-12 col-sm-4",children:[Object(c.jsx)("h4",{className:"visible-xs",children:e.name}),Object(c.jsx)("span",{className:"hidden-xs",children:e.host_name})]}),Object(c.jsxs)("div",{className:"col-xs-4 col-sm-2 right",children:[e.neighbourhood," ",e.neighbourhood_group]}),Object(c.jsx)("div",{className:"col-xs-8 col-sm-3",children:e.minimum_nights}),Object(c.jsxs)("div",{className:"col-xs-10 col-sm-2",children:[e.price,"$"]})]})},e.id)}))]})]})}function p(e){var t=e.id,s=Object(n.useState)({}),a=Object(o.a)(s,2),i=a[0],r=a[1],j=Object(n.useState)(!1),d=Object(o.a)(j,2),b=d[0],h=d[1],m=Object(l.g)();return Object(n.useEffect)((function(){h(!0),u.get("/api/szallasok/"+t).then((function(e){return e.data})).then((function(e){r(e),h(!1)})).catch((function(){h(!1),m.push("/")}))}),[]),b||!i.id?Object(c.jsx)("div",{className:"center-item",children:Object(c.jsx)("div",{className:"spinner-border text-danger"})}):Object(c.jsxs)("div",{className:"card w-100 m-auto p-3",children:[Object(c.jsx)("h1",{children:i.name}),Object(c.jsx)("h3",{children:i.host_name}),Object(c.jsxs)("h3",{children:[i.neighbourhood," ",i.neighbourhood_group]}),Object(c.jsx)("h3",{children:i.minimum_nights}),Object(c.jsx)(x,{})]})}function f(){return Object(c.jsx)(r.a,{children:Object(c.jsxs)(l.d,{children:[Object(c.jsx)(l.b,{path:"/bejelentkezes",exact:!0,component:m}),Object(c.jsx)(l.b,{path:"/osszes-szallas",component:O}),Object(c.jsx)(l.b,{path:"/szallas-:szallasId",children:function(e){return Object(c.jsx)(p,{id:e.match.params.szallasId})}}),Object(c.jsx)(l.a,{to:"/bejelentkezes"})]})})}u.interceptors.request.use((function(e){return h?Object(j.a)(Object(j.a)({},e),{},{headers:Object(j.a)(Object(j.a)({},e.headers),{},{Authorization:"Bearer ".concat(h)})}):e}),(function(e){return Promise.reject(e)})),u.interceptors.response.use((function(e){return e}),(function(e){var t=e.config;return t.isRetry?Promise.reject(e):(t.isRetry=!0,401!==e.response.status?Promise.reject(e):b.a.get("/api/get-new-access-token",{withCredentials:!0}).then((function(e){h=e.data.accessToken})).then((function(){return u(t)})))}));var g=function(e){e&&e instanceof Function&&s.e(3).then(s.bind(null,61)).then((function(t){var s=t.getCLS,c=t.getFID,n=t.getFCP,a=t.getLCP,i=t.getTTFB;s(e),c(e),n(e),a(e),i(e)}))};s(59);i.a.render(Object(c.jsx)(f,{}),document.getElementById("root")),g()}},[[60,1,2]]]);
//# sourceMappingURL=main.15179609.chunk.js.map