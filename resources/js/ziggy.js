const Ziggy = {"url":"http:\/\/ms-admin.test","port":null,"defaults":{},"routes":{"stancl.tenancy.asset":{"uri":"tenancy\/assets\/{path?}","methods":["GET","HEAD"],"wheres":{"path":"(.*)"},"parameters":["path"]},"home":{"uri":"\/","methods":["GET","HEAD"]},"dashboard":{"uri":"dashboard","methods":["GET","HEAD"],"domain":"ms-admin.test"},"roles.index":{"uri":"roles","methods":["GET","HEAD"],"domain":"ms-admin.test"},"roles.create":{"uri":"roles\/create","methods":["GET","HEAD"],"domain":"ms-admin.test"},"roles.store":{"uri":"roles","methods":["POST"],"domain":"ms-admin.test"},"roles.show":{"uri":"roles\/{role}","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["role"]},"roles.edit":{"uri":"roles\/{role}\/edit","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["role"]},"roles.update":{"uri":"roles\/{role}","methods":["PUT","PATCH"],"domain":"ms-admin.test","parameters":["role"]},"roles.destroy":{"uri":"roles\/{role}","methods":["DELETE"],"domain":"ms-admin.test","parameters":["role"],"bindings":{"role":"id"}},"users.index":{"uri":"users","methods":["GET","HEAD"],"domain":"ms-admin.test"},"users.create":{"uri":"users\/create","methods":["GET","HEAD"],"domain":"ms-admin.test"},"users.store":{"uri":"users","methods":["POST"],"domain":"ms-admin.test"},"users.show":{"uri":"users\/{user}","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["user"]},"users.edit":{"uri":"users\/{user}\/edit","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["user"]},"users.update":{"uri":"users\/{user}","methods":["PUT","PATCH"],"domain":"ms-admin.test","parameters":["user"]},"users.destroy":{"uri":"users\/{user}","methods":["DELETE"],"domain":"ms-admin.test","parameters":["user"],"bindings":{"user":"id"}},"tenants.index":{"uri":"tenants","methods":["GET","HEAD"],"domain":"ms-admin.test"},"tenants.create":{"uri":"tenants\/create","methods":["GET","HEAD"],"domain":"ms-admin.test"},"tenants.store":{"uri":"tenants","methods":["POST"],"domain":"ms-admin.test"},"tenants.show":{"uri":"tenants\/{tenant}","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["tenant"]},"tenants.edit":{"uri":"tenants\/{tenant}\/edit","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["tenant"]},"tenants.update":{"uri":"tenants\/{tenant}","methods":["PUT","PATCH"],"domain":"ms-admin.test","parameters":["tenant"]},"tenants.destroy":{"uri":"tenants\/{tenant}","methods":["DELETE"],"domain":"ms-admin.test","parameters":["tenant"],"bindings":{"tenant":"id"}},"profile.edit":{"uri":"settings\/profile","methods":["GET","HEAD"],"domain":"ms-admin.test"},"profile.update":{"uri":"settings\/profile","methods":["PATCH"],"domain":"ms-admin.test"},"profile.destroy":{"uri":"settings\/profile","methods":["DELETE"],"domain":"ms-admin.test"},"password.edit":{"uri":"settings\/password","methods":["GET","HEAD"],"domain":"ms-admin.test"},"password.update":{"uri":"settings\/password","methods":["PUT"],"domain":"ms-admin.test"},"appearance":{"uri":"settings\/appearance","methods":["GET","HEAD"],"domain":"ms-admin.test"},"register":{"uri":"register","methods":["GET","HEAD"],"domain":"ms-admin.test"},"login":{"uri":"login","methods":["GET","HEAD"],"domain":"ms-admin.test"},"password.request":{"uri":"forgot-password","methods":["GET","HEAD"],"domain":"ms-admin.test"},"password.email":{"uri":"forgot-password","methods":["POST"],"domain":"ms-admin.test"},"password.reset":{"uri":"reset-password\/{token}","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["token"]},"password.store":{"uri":"reset-password","methods":["POST"],"domain":"ms-admin.test"},"verification.notice":{"uri":"verify-email","methods":["GET","HEAD"],"domain":"ms-admin.test"},"verification.verify":{"uri":"verify-email\/{id}\/{hash}","methods":["GET","HEAD"],"domain":"ms-admin.test","parameters":["id","hash"]},"verification.send":{"uri":"email\/verification-notification","methods":["POST"],"domain":"ms-admin.test"},"password.confirm":{"uri":"confirm-password","methods":["GET","HEAD"],"domain":"ms-admin.test"},"logout":{"uri":"logout","methods":["POST"],"domain":"ms-admin.test"},"storage.local":{"uri":"storage\/{path}","methods":["GET","HEAD"],"wheres":{"path":".*"},"parameters":["path"]}}};
if (typeof window !== 'undefined' && typeof window.Ziggy !== 'undefined') {
  Object.assign(Ziggy.routes, window.Ziggy.routes);
}
export { Ziggy };
