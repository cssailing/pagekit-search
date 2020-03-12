<template>
    <div class="uk-overflow-container">
        <table class="uk-table uk-table-hover uk-table-middle uk-table-condensed">
            <thead>
                <tr>
                    <th class="pk-table-min-width-200" v-order:word="config.filter.order2">{{ 'Search keywords' | trans }}</th>
                    <th class="pk-table-width-100 uk-text-center" v-order:wcount="config.filter.order2">{{ 'Count' | trans }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(keyword,index) in keywords" :key="index">
                    <td> {{ keyword.word }} </td>
                    <td class="uk-text-center"> {{ keyword.wcount }} </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>
<script>
const TemplateSumm = {
    name: 'template-summ',
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

export default TemplateSumm;
Vue.ready(TemplateSumm);
</script>