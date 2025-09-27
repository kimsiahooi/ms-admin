import {
    h as _,
    f as B,
    u as e,
    g as h,
    o as i,
    F as k,
    i as L,
    b as m,
    c as n,
    x as N,
    a as p,
    e as r,
    v as R,
    j as T,
    w as u,
    q as v,
    d as x,
} from './app-DW3qUSpG.js';
import { u as G, _ as H } from './AppLayout.vue_vue_type_script_setup_true_lang-BgvfEKRj.js';
import './AppLogoIcon.vue_vue_type_script_setup_true_lang-DsKetNlE.js';
import { _ as O } from './AppMainLayout.vue_vue_type_script_setup_true_lang-o02Mcb03.js';
import './BreadcrumbSeparator.vue_vue_type_script_setup_true_lang-vj4ZpyyN.js';
import { _ as D } from './Card.vue_vue_type_script_setup_true_lang-DZHqgzGk.js';
import './check-CxD0QyHG.js';
import './chevron-down-MWTkW-nX.js';
import './FormCombobox.vue_vue_type_script_setup_true_lang-D8IKo5CC.js';
import { _ as A } from './FormError.vue_vue_type_script_setup_true_lang-1af55uzd.js';
import { _ as b } from './FormInput.vue_vue_type_script_setup_true_lang-Btfx0rSq.js';
import { _ as I } from './FormSwitch.vue_vue_type_script_setup_true_lang-zC1jdb_M.js';
import { _ as P } from './FormTextarea.vue_vue_type_script_setup_true_lang-V-SYeW4h.js';
import { L as j } from './index-Bi-HJBVM.js';
import { _ as E } from './index-CgwL-eGe.js';
import { a as M, S as q, _ as z } from './index-CU-F_MNW.js';
import './Input.vue_vue_type_script_setup_true_lang-JOJUwJ_8.js';
import { _ as F } from './Label.vue_vue_type_script_setup_true_lang-Mfd-XF4S.js';
import './RovingFocusGroup-RdfVjWf9.js';
import { s as Q } from './slug-xwoAxeGq.js';
import './Switch.vue_vue_type_script_setup_true_lang-C_KlnxJK.js';
import './useFormControl-D4ksLBo0.js';
import './useForwardExpose-CvcD0Rb6.js';
import { u as J, P as K } from './useUuid-BVk90Rr7.js';
import './VisuallyHiddenInput-ChKgx7vy.js';
const W = { class: 'space-y-3' },
    X = { class: 'space-y-3' },
    Y = { class: 'flex items-center justify-center' },
    Se = x({
        layout: O,
        __name: 'Create',
        props: { plants: {}, options: {} },
        setup(y) {
            const c = y,
                { tenant: o } = G(),
                { uuid: f } = J(),
                V = n(() => [
                    { title: 'Dashboard', href: route('dashboard', { tenant: (o == null ? void 0 : o.id) || '' }) },
                    { title: 'Routes', href: route('routes.index', { tenant: (o == null ? void 0 : o.id) || '' }) },
                    { title: 'Create', href: route('routes.create', { tenant: (o == null ? void 0 : o.id) || '' }) },
                ]),
                C = n(() => {
                    var a;
                    return !!((a = c.options.switch.statuses.find((s) => s.is_default)) != null && a.value);
                }),
                $ = n(() => {
                    var a;
                    return ((a = c.options.switch.statuses.find((s) => s.value === t.status)) == null ? void 0 : a.name) ?? q[M.INACTIVE];
                }),
                t = N({ name: '', code: '', description: '', status: C.value, tasks: [{ key: f(), plant_id: '', department_id: '', task_id: '' }] }),
                g = () =>
                    t.post(route('routes.store', { tenant: (o == null ? void 0 : o.id) || '' }), {
                        preserveScroll: !0,
                        preserveState: !0,
                        onSuccess: () => {
                            t.reset();
                        },
                    }),
                S = () => {
                    t.tasks = [...t.tasks, { key: f(), plant_id: '', department_id: '', task_id: '' }];
                },
                U = (a) => {
                    t.tasks = t.tasks.filter((s) => s.key !== a);
                };
            return (
                T(
                    () => t.name,
                    (a) => {
                        t.code = Q(a);
                    },
                ),
                (a, s) => (
                    i(),
                    p(
                        k,
                        null,
                        [
                            r(e(h), { title: 'Create Route' }),
                            r(
                                H,
                                { breadcrumbs: V.value },
                                {
                                    default: u(() => [
                                        r(e(j), null, {
                                            default: u(() => [
                                                m(
                                                    'form',
                                                    { onSubmit: B(g, ['prevent']) },
                                                    [
                                                        r(
                                                            e(D),
                                                            { title: 'Create Route' },
                                                            {
                                                                default: u(() => [
                                                                    r(
                                                                        e(b),
                                                                        {
                                                                            label: 'Name',
                                                                            error: e(t).errors.name,
                                                                            'model-value': e(t).name,
                                                                            'onUpdate:modelValue': s[0] || (s[0] = (l) => (e(t).name = l)),
                                                                        },
                                                                        null,
                                                                        8,
                                                                        ['error', 'model-value'],
                                                                    ),
                                                                    r(
                                                                        e(b),
                                                                        {
                                                                            label: 'Code',
                                                                            error: e(t).errors.code,
                                                                            'model-value': e(t).code,
                                                                            'onUpdate:modelValue': s[1] || (s[1] = (l) => (e(t).code = l)),
                                                                        },
                                                                        null,
                                                                        8,
                                                                        ['error', 'model-value'],
                                                                    ),
                                                                    r(
                                                                        e(P),
                                                                        {
                                                                            label: 'Description',
                                                                            error: e(t).errors.description,
                                                                            'model-value': e(t).description,
                                                                            'onUpdate:modelValue': s[2] || (s[2] = (l) => (e(t).description = l)),
                                                                        },
                                                                        null,
                                                                        8,
                                                                        ['error', 'model-value'],
                                                                    ),
                                                                    m('div', W, [
                                                                        m('div', null, [
                                                                            r(
                                                                                e(F),
                                                                                { class: 'mb-1' },
                                                                                { default: u(() => s[4] || (s[4] = [v('Tasks:')])), _: 1, __: [4] },
                                                                            ),
                                                                        ]),
                                                                        m('div', X, [
                                                                            (i(!0),
                                                                            p(
                                                                                k,
                                                                                null,
                                                                                R(
                                                                                    e(t).tasks,
                                                                                    (l, d) => (
                                                                                        i(),
                                                                                        p('div', { key: l.key, class: 'space-y-1' }, [
                                                                                            (i(),
                                                                                            _(
                                                                                                e(z),
                                                                                                {
                                                                                                    key: l.key,
                                                                                                    plants: a.plants,
                                                                                                    'total-selected': e(t).tasks.length,
                                                                                                    'curr-index': d,
                                                                                                    'model-value': e(t).tasks[d],
                                                                                                    'onUpdate:modelValue': (w) => (e(t).tasks[d] = w),
                                                                                                    onRemove: U,
                                                                                                },
                                                                                                null,
                                                                                                8,
                                                                                                [
                                                                                                    'plants',
                                                                                                    'total-selected',
                                                                                                    'curr-index',
                                                                                                    'model-value',
                                                                                                    'onUpdate:modelValue',
                                                                                                ],
                                                                                            )),
                                                                                        ])
                                                                                    ),
                                                                                ),
                                                                                128,
                                                                            )),
                                                                        ]),
                                                                        m('div', Y, [
                                                                            r(
                                                                                e(E),
                                                                                {
                                                                                    type: 'button',
                                                                                    class: 'cursor-pointer',
                                                                                    variant: 'outline',
                                                                                    onClick: S,
                                                                                },
                                                                                {
                                                                                    default: u(() => [r(e(K)), s[5] || (s[5] = v(' Add Task'))]),
                                                                                    _: 1,
                                                                                    __: [5],
                                                                                },
                                                                            ),
                                                                        ]),
                                                                    ]),
                                                                    e(t).status !== void 0
                                                                        ? (i(),
                                                                          _(
                                                                              e(I),
                                                                              {
                                                                                  key: 0,
                                                                                  label: $.value,
                                                                                  error: e(t).errors.status,
                                                                                  'model-value': e(t).status,
                                                                                  'onUpdate:modelValue': s[3] || (s[3] = (l) => (e(t).status = l)),
                                                                              },
                                                                              null,
                                                                              8,
                                                                              ['label', 'error', 'model-value'],
                                                                          ))
                                                                        : L('', !0),
                                                                    r(
                                                                        e(A),
                                                                        { type: 'submit', disabled: e(t).processing, loading: e(t).processing },
                                                                        null,
                                                                        8,
                                                                        ['disabled', 'loading'],
                                                                    ),
                                                                ]),
                                                                _: 1,
                                                            },
                                                        ),
                                                    ],
                                                    32,
                                                ),
                                            ]),
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
                )
            );
        },
    });
export { Se as default };
