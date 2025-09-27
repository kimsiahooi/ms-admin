import { g as $, c as d, F as E, o as f, x as g, e as i, u as r, a as S, w as u, b as U, d as v, f as V, h as w, i as y } from './app-DW3qUSpG.js';
import { _ as D, u as L } from './AppLayout.vue_vue_type_script_setup_true_lang-BgvfEKRj.js';
import './AppLogoIcon.vue_vue_type_script_setup_true_lang-DsKetNlE.js';
import { _ as F } from './AppMainLayout.vue_vue_type_script_setup_true_lang-o02Mcb03.js';
import './BreadcrumbSeparator.vue_vue_type_script_setup_true_lang-vj4ZpyyN.js';
import { _ as k } from './Card.vue_vue_type_script_setup_true_lang-DZHqgzGk.js';
import './check-CxD0QyHG.js';
import './chevron-down-MWTkW-nX.js';
import { _ as C } from './FormCombobox.vue_vue_type_script_setup_true_lang-D8IKo5CC.js';
import { _ as B } from './FormError.vue_vue_type_script_setup_true_lang-1af55uzd.js';
import { _ as N } from './FormSwitch.vue_vue_type_script_setup_true_lang-zC1jdb_M.js';
import { L as x } from './index-Bi-HJBVM.js';
import './index-CgwL-eGe.js';
import { S as I, a as T } from './index-tNKZbeW-.js';
import './Label.vue_vue_type_script_setup_true_lang-Mfd-XF4S.js';
import './RovingFocusGroup-RdfVjWf9.js';
import './Switch.vue_vue_type_script_setup_true_lang-C_KlnxJK.js';
import './useFormControl-D4ksLBo0.js';
import './useForwardExpose-CvcD0Rb6.js';
import './VisuallyHiddenInput-ChKgx7vy.js';
const se = v({
    layout: F,
    __name: 'Edit',
    props: { plant: {}, department: {}, user: {}, options: {} },
    setup(c) {
        var n;
        const t = c,
            { tenant: e } = L(),
            _ = d(() => {
                var o;
                return [
                    { title: 'Dashboard', href: route('dashboard', { tenant: (e == null ? void 0 : e.id) || '' }) },
                    { title: 'Plants', href: route('plants.index', { tenant: (e == null ? void 0 : e.id) || '' }) },
                    { title: t.plant.name, href: '#' },
                    {
                        title: 'Departments',
                        href: route('plants.departments.index', { tenant: (e == null ? void 0 : e.id) || '', plant: t.plant.id }),
                    },
                    { title: t.department.name, href: '#' },
                    {
                        title: 'Users',
                        href: route('plants.departments.users.index', {
                            tenant: (e == null ? void 0 : e.id) || '',
                            plant: t.plant.id,
                            department: t.department.id,
                        }),
                    },
                    { title: ((o = t.user.user) == null ? void 0 : o.name) ?? '', href: '#' },
                    {
                        title: 'Edit',
                        href: route('plants.departments.users.edit', {
                            tenant: (e == null ? void 0 : e.id) || '',
                            plant: t.plant.id,
                            department: t.department.id,
                            user: t.user.id,
                        }),
                    },
                ];
            }),
            b = d(() => {
                var o;
                return ((o = t.options.switch.statuses.find((a) => a.value === s.status)) == null ? void 0 : o.name) ?? I[T.INACTIVE];
            }),
            s = g({ user: (n = t.user.user) == null ? void 0 : n.id, status: t.user.status.switch ?? void 0 }),
            h = () =>
                s.put(
                    route('plants.departments.users.update', {
                        tenant: (e == null ? void 0 : e.id) || '',
                        plant: t.plant.id,
                        department: t.department.id,
                        user: t.user.id,
                    }),
                    { preserveScroll: !0, preserveState: !0 },
                );
        return (o, a) => {
            var p;
            return (
                f(),
                S(
                    E,
                    null,
                    [
                        i(r($), { title: (p = o.user.user) == null ? void 0 : p.name }, null, 8, ['title']),
                        i(
                            D,
                            { breadcrumbs: _.value },
                            {
                                default: u(() => [
                                    i(r(x), null, {
                                        default: u(() => {
                                            var m;
                                            return [
                                                U(
                                                    'form',
                                                    { onSubmit: V(h, ['prevent']) },
                                                    [
                                                        i(
                                                            r(k),
                                                            { title: `Edit ${(m = o.user.user) == null ? void 0 : m.name}` },
                                                            {
                                                                default: u(() => [
                                                                    i(
                                                                        r(C),
                                                                        {
                                                                            options: o.options.select.users,
                                                                            placeholder: 'Select User',
                                                                            label: 'User',
                                                                            'model-value': r(s).user,
                                                                            'onUpdate:modelValue': a[0] || (a[0] = (l) => (r(s).user = l)),
                                                                            error: r(s).errors.user,
                                                                        },
                                                                        null,
                                                                        8,
                                                                        ['options', 'model-value', 'error'],
                                                                    ),
                                                                    r(s).status !== void 0
                                                                        ? (f(),
                                                                          w(
                                                                              r(N),
                                                                              {
                                                                                  key: 0,
                                                                                  label: b.value,
                                                                                  error: r(s).errors.status,
                                                                                  'model-value': r(s).status,
                                                                                  'onUpdate:modelValue': a[1] || (a[1] = (l) => (r(s).status = l)),
                                                                              },
                                                                              null,
                                                                              8,
                                                                              ['label', 'error', 'model-value'],
                                                                          ))
                                                                        : y('', !0),
                                                                    i(
                                                                        r(B),
                                                                        {
                                                                            type: 'submit',
                                                                            disabled: r(s).processing,
                                                                            label: 'Update',
                                                                            loading: r(s).processing,
                                                                        },
                                                                        null,
                                                                        8,
                                                                        ['disabled', 'loading'],
                                                                    ),
                                                                ]),
                                                                _: 1,
                                                            },
                                                            8,
                                                            ['title'],
                                                        ),
                                                    ],
                                                    32,
                                                ),
                                            ];
                                        }),
                                        _: 1,
                                    }),
                                ]),
                                _: 1,
                            },
                            8,
                            ['breadcrumbs'],
                        ),
                    ],
                    64,
                )
            );
        };
    },
});
export { se as default };
