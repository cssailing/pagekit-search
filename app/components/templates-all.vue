<template>
    <div class="uk-overflow-container">
        <table class="uk-table uk-table-hover uk-table-middle uk-table-condensed">
            <thead>
                <tr>
                    <th class="pk-table-min-width-200" v-order:word="config.filter.order">{{ 'Search keywords' | trans }}</th>
                    <th class="pk-table-width-100 uk-text-center" v-order:ip="config.filter.order">{{ 'Ip' | trans }}</th>
                    <th class="pk-table-width-150 uk-text-center" v-order:putdate="config.filter.order">{{ 'Date' | trans }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(keyword,index) in keywords" :key="index">
                    <td> {{ keyword.word }} </td>
                    <td class="uk-text-center"> {{ keyword.ip }} </td>
                    <td class="uk-text-center"> {{ keyword.putdate | date }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
const TemplateAll = {
    name: 'template-all',
    data() {
        return {
            config: {
                filter: this.$session.get('statistics.filter', { order: 'putdate desc', order2: 'wcount desc', limit: 25 })
            },
        }
    },
    replace: false,
    props: ['keywords'],
};

export default TemplateAll;
Vue.ready(TemplateAll);
</script>