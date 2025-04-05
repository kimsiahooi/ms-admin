/* This file is generated by Ziggy. */
declare module 'ziggy-js' {
  interface RouteList {
    "stancl.tenancy.asset": [
        {
            "name": "path",
            "required": false
        }
    ],
    "home": [],
    "dashboard": [],
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
            "required": true
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
