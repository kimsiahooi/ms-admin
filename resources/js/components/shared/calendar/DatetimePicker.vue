<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Calendar } from '@/components/ui/calendar';
import { Popover, PopoverContent, PopoverTrigger } from '@/components/ui/popover';
import { ScrollArea, ScrollBar } from '@/components/ui/scroll-area';
import { useFormatDateTime } from '@/composables/useFormatDateTime';
import { CalendarDate, type DateValue } from '@internationalized/date';
import { CalendarIcon } from 'lucide-vue-next';
import { computed } from 'vue';

const hours = Array.from({ length: 12 }, (_, i) => i + 1).reverse();
const minutes = Array.from({ length: 60 }, (_, i) => i);

const model = defineModel<Date>();

const { formatDateTime } = useFormatDateTime();

const handleDateSelect = (selectedDate: DateValue | undefined) => {
    if (selectedDate) {
        const currentHours = model.value ? new Date(model.value).getHours() : null;
        const currentMinutes = model.value ? new Date(model.value).getMinutes() : null;
        const newDate = new Date(selectedDate.toString());
        if (currentHours) {
            newDate.setHours(currentHours);
        } else {
            newDate.setHours(0);
        }

        if (currentMinutes) {
            newDate.setMinutes(currentMinutes);
        }

        model.value = newDate;
    }
};

const handleTimeChange = (type: 'hour' | 'minute' | 'ampm', value: string) => {
    if (model.value) {
        const newDate = new Date(model.value);
        if (type === 'hour') {
            const currentHours = model.value.getHours();
            if (currentHours >= 12) {
                newDate.setHours((+value % 12) + 12);
            } else {
                newDate.setHours(+value % 12);
            }
        } else if (type === 'minute') {
            newDate.setMinutes(+value);
        } else if (type === 'ampm') {
            const currentHours = newDate.getHours();
            if (currentHours >= 12 && value === 'AM') {
                newDate.setHours(currentHours - 12);
            }

            if (currentHours < 12 && value === 'PM') {
                newDate.setHours(currentHours + 12);
            }
        }

        model.value = newDate;
    }
};

//  computed
const dateResult = computed(() => (model.value ? formatDateTime(model.value, 'YYYY-MM-DD hh:mm:ssa') : 'YYYY-MM-DD hh:mm:ssa'));

const calendarDate = computed(() =>
    model.value ? new CalendarDate(model.value.getFullYear(), model.value.getMonth() + 1, model.value.getDate()) : undefined,
);
</script>

<template>
    <div>
        <Popover>
            <PopoverTrigger as-child>
                <Button
                    variant="outline"
                    class="w-full justify-start text-left font-normal"
                    :class="{
                        'text-muted-foreground': !model,
                    }"
                >
                    <CalendarIcon class="mr-2 h-4 w-4" />
                    {{ dateResult }}
                </Button>
            </PopoverTrigger>
            <PopoverContent class="w-auto p-0">
                <div class="sm:flex">
                    <Calendar :default-value="calendarDate" @update:model-value="handleDateSelect" />
                    <div class="flex flex-col divide-y sm:h-[300px] sm:flex-row sm:divide-x sm:divide-y-0">
                        <ScrollArea class="w-64 sm:w-auto">
                            <div class="flex p-2 sm:flex-col">
                                <Button
                                    v-for="hour in hours"
                                    :key="hour"
                                    size="icon"
                                    :variant="model && model.getHours() % 12 === hour % 12 ? 'default' : 'ghost'"
                                    class="aspect-square shrink-0 sm:w-full"
                                    @click="handleTimeChange('hour', hour.toString())"
                                >
                                    {{ hour }}
                                </Button>
                            </div>
                            <ScrollBar orientation="horizontal" class="sm:hidden" />
                        </ScrollArea>
                        <ScrollArea class="w-64 sm:w-auto">
                            <div class="flex p-2 sm:flex-col">
                                <Button
                                    v-for="minute in minutes"
                                    :key="minute"
                                    size="icon"
                                    :variant="model && model.getMinutes() === minute ? 'default' : 'ghost'"
                                    class="aspect-square shrink-0 sm:w-full"
                                    @click="handleTimeChange('minute', minute.toString())"
                                >
                                    {{ minute }}
                                </Button>
                            </div>
                            <ScrollBar orientation="horizontal" class="sm:hidden" />
                        </ScrollArea>
                        <ScrollArea class="">
                            <div class="flex p-2 sm:flex-col">
                                <Button
                                    v-for="ampm in ['AM', 'PM']"
                                    :key="ampm"
                                    size="icon"
                                    :variant="
                                        model && ((ampm === 'AM' && model.getHours() < 12) || (ampm === 'PM' && model.getHours() >= 12))
                                            ? 'default'
                                            : 'ghost'
                                    "
                                    class="aspect-square shrink-0 sm:w-full"
                                    @click="handleTimeChange('ampm', ampm)"
                                >
                                    {{ ampm }}
                                </Button>
                            </div>
                        </ScrollArea>
                    </div>
                </div>
            </PopoverContent>
        </Popover>
    </div>
</template>
