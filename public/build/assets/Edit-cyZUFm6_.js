import { f as $, x as _, o as d, u as e, h as g, g as h, d as k, w as n, c as p, e as s, i as S, a as v, b as V, F as w } from './app-DW3qUSpG.js';
import { _ as B, u as U } from './AppLayout.vue_vue_type_script_setup_true_lang-BgvfEKRj.js';
import './AppLogoIcon.vue_vue_type_script_setup_true_lang-DsKetNlE.js';
import { _ as D } from './AppMainLayout.vue_vue_type_script_setup_true_lang-o02Mcb03.js';
import './BreadcrumbSeparator.vue_vue_type_script_setup_true_lang-vj4ZpyyN.js';
import { _ as x } from './Card.vue_vue_type_script_setup_true_lang-DZHqgzGk.js';
import { _ as C } from './FormError.vue_vue_type_script_setup_true_lang-1af55uzd.js';
import { _ as m } from './FormInput.vue_vue_type_script_setup_true_lang-Btfx0rSq.js';
import { _ as E } from './FormSwitch.vue_vue_type_script_setup_true_lang-zC1jdb_M.js';
import { _ as N } from './FormTextarea.vue_vue_type_script_setup_true_lang-V-SYeW4h.js';
import { L as y } from './index-Bi-HJBVM.js';
import './index-CgwL-eGe.js';
import { S as L, a as T } from './index-tNKZbeW-.js';
import './Input.vue_vue_type_script_setup_true_lang-JOJUwJ_8.js';
import './Label.vue_vue_type_script_setup_true_lang-Mfd-XF4S.js';
import './RovingFocusGroup-RdfVjWf9.js';
import './Switch.vue_vue_type_script_setup_true_lang-C_KlnxJK.js';
import './useFormControl-D4ksLBo0.js';
import './useForwardExpose-CvcD0Rb6.js';
import './VisuallyHiddenInput-ChKgx7vy.js';
const te = k({
    layout: D,
    __name: 'Edit',
    props: { plant: {}, department: {}, task: {}, options: {} },
    setup(u) {
        const a = u,
            { tenant: t } = U(),
            f = p(() => [
                { title: 'Dashboard', href: route('dashboard', { tenant: (t == null ? void 0 : t.id) || '' }) },
                { title: 'Plants', href: route('plants.index', { tenant: (t == null ? void 0 : t.id) || '' }) },
                { title: a.plant.name, href: '#' },
                { title: 'Departments', href: route('plants.departments.index', { tenant: (t == null ? void 0 : t.id) || '', plant: a.plant.id }) },
                { title: a.department.name, href: '#' },
                {
                    title: 'Tasks',
                    href: route('plants.departments.tasks.index', {
                        tenant: (t == null ? void 0 : t.id) || '',
                        plant: a.plant.id,
                        department: a.department.id,
                    }),
                },
                { title: a.task.name, href: '#' },
                {
                    title: 'Edit',
                    href: route('plants.departments.tasks.edit', {
                        tenant: (t == null ? void 0 : t.id) || '',
                        plant: a.plant.id,
                        department: a.department.id,
                        task: a.task.id,
                    }),
                },
            ]),
            c = p(() => {
                var l;
                return ((l = a.options.switch.statuses.find((r) => r.value === o.status)) == null ? void 0 : l.name) ?? L[T.INACTIVE];
            }),
            o = _({ name: a.task.name, code: a.task.code, description: a.task.description || '', status: a.task.status.switch ?? void 0 }),
            b = () =>
                o.put(
                    route('plants.departments.tasks.update', {
                        tenant: (t == null ? void 0 : t.id) || '',
                        plant: a.plant.id,
                        department: a.department.id,
                        task: a.task.id,
                    }),
                    { preserveScroll: !0, preserveState: !0 },
                );
        return (l, r) => (
            d(),
            v(
                w,
                null,
                [
                    s(e(h), { title: l.task.name }, null, 8, ['title']),
                    s(
                        B,
                        { breadcrumbs: f.value },
                        {
                            default: n(() => [
                                s(e(y), null, {
                                    default: n(() => [
                                        V(
                                            'form',
                                            { onSubmit: $(b, ['prevent']) },
                                            [
                                                s(
                                                    e(x),
                                                    { title: `Edit ${l.task.name}` },
                                                    {
                                                        default: n(() => [
                                                            s(
                                                                e(m),
                                                                {
                                                                    label: 'Name',
                                                                    error: e(o).errors.name,
                                                                    'model-value': e(o).name,
                                                                    'onUpdate:modelValue': r[0] || (r[0] = (i) => (e(o).name = i)),
                                                                },
                                                                null,
                                                                8,
                                                                ['error', 'model-value'],
                                                            ),
                                                            s(
                                                                e(m),
                                                                {
                                                                    label: 'Code',
                                                                    error: e(o).errors.code,
                                                                    'model-value': e(o).code,
                                                                    'onUpdate:modelValue': r[1] || (r[1] = (i) => (e(o).code = i)),
                                                                },
                                                                null,
                                                                8,
                                                                ['error', 'model-value'],
                                                            ),
                                                            s(
                                                                e(N),
                                                                {
                                                                    label: 'Description',
                                                                    error: e(o).errors.description,
                                                                    'model-value': e(o).description,
                                                                    'onUpdate:modelValue': r[2] || (r[2] = (i) => (e(o).description = i)),
                                                                },
                                                                null,
                                                                8,
                                                                ['error', 'model-value'],
                                                            ),
                                                            e(o).status !== void 0
                                                                ? (d(),
                                                                  g(
                                                                      e(E),
                                                                      {
                                                                          key: 0,
                                                                          label: c.value,
                                                                          error: e(o).errors.status,
                                                                          'model-value': e(o).status,
                                                                          'onUpdate:modelValue': r[3] || (r[3] = (i) => (e(o).status = i)),
                                                                      },
                                                                      null,
                                                                      8,
                                                                      ['label', 'error', 'model-value'],
                                                                  ))
                                                                : S('', !0),
                                                            s(
                                                                e(C),
                                                                {
                                                                    type: 'submit',
                                                                    disabled: e(o).processing,
                                                                    label: 'Update',
                                                                    loading: e(o).processing,
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
        );
    },
});
export { te as default };
