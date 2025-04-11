/* This file is generated by Ziggy. */
declare module 'ziggy-js' {
  interface RouteList {
    "debugbar.openhandler": [],
    "debugbar.clockwork": [
        {
            "name": "id",
            "required": true
        }
    ],
    "debugbar.assets.css": [],
    "debugbar.assets.js": [],
    "debugbar.cache.delete": [
        {
            "name": "key",
            "required": true
        },
        {
            "name": "tags",
            "required": false
        }
    ],
    "debugbar.queries.explain": [],
    "stancl.tenancy.asset": [
        {
            "name": "path",
            "required": false
        }
    ],
    "home": [],
    "dashboard": [],
    "roles.index": [],
    "roles.create": [],
    "roles.store": [],
    "roles.edit": [
        {
            "name": "role",
            "required": true,
            "binding": "id"
        }
    ],
    "roles.update": [
        {
            "name": "role",
            "required": true,
            "binding": "id"
        }
    ],
    "roles.destroy": [
        {
            "name": "role",
            "required": true,
            "binding": "id"
        }
    ],
    "roles.updatePermissions": [
        {
            "name": "role",
            "required": true,
            "binding": "id"
        }
    ],
    "users.index": [],
    "users.create": [],
    "users.store": [],
    "users.edit": [
        {
            "name": "user",
            "required": true,
            "binding": "id"
        }
    ],
    "users.update": [
        {
            "name": "user",
            "required": true,
            "binding": "id"
        }
    ],
    "users.destroy": [
        {
            "name": "user",
            "required": true,
            "binding": "id"
        }
    ],
    "users.trashed": [],
    "users.restore": [
        {
            "name": "user",
            "required": true
        }
    ],
    "users.forceDelete": [
        {
            "name": "user",
            "required": true
        }
    ],
    "users.audits": [],
    "tenants.index": [],
    "tenants.create": [],
    "tenants.store": [],
    "tenants.show": [
        {
            "name": "tenant",
            "required": true
        }
    ],
    "tenants.edit": [
        {
            "name": "tenant",
            "required": true
        }
    ],
    "tenants.update": [
        {
            "name": "tenant",
            "required": true
        }
    ],
    "tenants.destroy": [
        {
            "name": "tenant",
            "required": true,
            "binding": "id"
        }
    ],
    "profile.edit": [],
    "profile.update": [],
    "profile.destroy": [],
    "password.edit": [],
    "password.update": [],
    "appearance": [],
    "register": [],
    "login": [],
    "password.request": [],
    "password.email": [],
    "password.reset": [
        {
            "name": "token",
            "required": true
        }
    ],
    "password.store": [],
    "verification.notice": [],
    "verification.verify": [
        {
            "name": "id",
            "required": true
        },
        {
            "name": "hash",
            "required": true
        }
    ],
    "verification.send": [],
    "password.confirm": [],
    "logout": [],
    "storage.local": [
        {
            "name": "path",
            "required": true
        }
    ]
}
}
export {};
