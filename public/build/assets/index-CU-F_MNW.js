import { i as B, j as d, a as I, e as i, b2 as k, c as m, u as n, b as r, d as S, bE as U, o as V, A as w, w as y } from './app-DW3qUSpG.js';
import { _ as p } from './FormCombobox.vue_vue_type_script_setup_true_lang-D8IKo5CC.js';
import './FormError.vue_vue_type_script_setup_true_lang-1af55uzd.js';
import { _ as M } from './index-CgwL-eGe.js';
import { u as $, M as O } from './useUuid-BVk90Rr7.js';
const g = { class: 'flex flex-col gap-2 md:flex-row' },
    h = { class: 'flex-1' },
    j = { class: 'flex-1' },
    z = { class: 'flex-1' },
    L = { key: 0 },
    J = S({
        __name: 'TaskSelect',
        props: k({ plants: {}, totalSelected: {}, currIndex: {} }, { modelValue: {}, modelModifiers: {} }),
        emits: k(['remove'], ['update:modelValue']),
        setup(s, { emit: C }) {
            var v, _, c, f;
            const u = s,
                l = U(s, 'modelValue'),
                { uuid: x } = $(),
                b = C,
                a = w({
                    key: ((v = l.value) == null ? void 0 : v.key) ?? x(),
                    plant_id: (_ = l.value) == null ? void 0 : _.plant_id,
                    department_id: (c = l.value) == null ? void 0 : c.department_id,
                    task_id: (f = l.value) == null ? void 0 : f.task_id,
                }),
                A = m(() => u.plants.map((e) => ({ name: e.name, value: e.id }))),
                E = m(() => {
                    var e;
                    return (
                        ((e = u.plants.find((o) => o.id === a.value.plant_id)) == null
                            ? void 0
                            : e.departments.map((o) => ({ name: o.name, value: o.id }))) ?? []
                    );
                }),
                T = m(() => {
                    var e, o;
                    return (
                        ((o =
                            (e = u.plants.find((t) => t.id === a.value.plant_id)) == null
                                ? void 0
                                : e.departments.find((t) => t.id === a.value.department_id)) == null
                            ? void 0
                            : o.tasks.map((t) => ({ name: t.name, value: t.id }))) ?? []
                    );
                }),
                N = () => {
                    b('remove', a.value.key);
                };
            return (
                d(
                    () => a.value.plant_id,
                    () => {
                        a.value.department_id = void 0;
                    },
                ),
                d(
                    () => a.value.department_id,
                    () => {
                        a.value.task_id = void 0;
                    },
                ),
                d(
                    l,
                    (e) => {
                        e && (a.value = e);
                    },
                    { deep: !0 },
                ),
                d(
                    a,
                    (e) => {
                        l.value && (l.value = e);
                    },
                    { deep: !0 },
                ),
                (e, o) => (
                    V(),
                    I('div', null, [
                        r('div', g, [
                            r('div', h, [
                                i(
                                    n(p),
                                    {
                                        label: 'Plant',
                                        options: A.value,
                                        'model-value': a.value.plant_id,
                                        'onUpdate:modelValue': o[0] || (o[0] = (t) => (a.value.plant_id = t)),
                                        'error-key': `tasks.${e.currIndex}.plant_id`,
                                    },
                                    null,
                                    8,
                                    ['options', 'model-value', 'error-key'],
                                ),
                            ]),
                            r('div', j, [
                                i(
                                    n(p),
                                    {
                                        label: 'Department',
                                        options: E.value,
                                        'model-value': a.value.department_id,
                                        'onUpdate:modelValue': o[1] || (o[1] = (t) => (a.value.department_id = t)),
                                        'error-key': `tasks.${e.currIndex}.department_id`,
                                    },
                                    null,
                                    8,
                                    ['options', 'model-value', 'error-key'],
                                ),
                            ]),
                            r('div', z, [
                                i(
                                    n(p),
                                    {
                                        label: 'Task',
                                        options: T.value,
                                        'model-value': a.value.task_id,
                                        'onUpdate:modelValue': o[2] || (o[2] = (t) => (a.value.task_id = t)),
                                        'error-key': `tasks.${e.currIndex}.task_id`,
                                    },
                                    {
                                        default: y(() => [
                                            e.totalSelected > 1
                                                ? (V(),
                                                  I('div', L, [
                                                      i(
                                                          n(M),
                                                          {
                                                              type: 'button',
                                                              class: 'cursor-pointer',
                                                              size: 'icon',
                                                              variant: 'destructive',
                                                              onClick: N,
                                                          },
                                                          { default: y(() => [i(n(O))]), _: 1 },
                                                      ),
                                                  ]))
                                                : B('', !0),
                                        ]),
                                        _: 1,
                                    },
                                    8,
                                    ['options', 'model-value', 'error-key'],
                                ),
                            ]),
                        ]),
                    ])
                )
            );
        },
    });
var P = ((s) => ((s.ACTIVE = 'ACTIVE'), (s.INACTIVE = 'INACTIVE'), s))(P || {});
const K = { ACTIVE: 'Active', INACTIVE: 'Inactive' };
export { J as _, P as a, K as S };
