<script setup>
    /* global copyTextToClipboard */
    import {computed, ref} from "vue";

    const props = defineProps({
        initial_request: {
            type: Object,
            required: false
        },
        ajax_requests: {
            type: Array,
            required: false
        },
        current_profile: {
            type: Object,
            required: false
        },
    });

    const is_global_mode = computed(() => {
        return props.current_profile === undefined && props.ajax_requests !== undefined;
    });

    function getCombinedSQLData() {
        const sql_data = {
            total_requests: 0,
            total_duration: 0,
            queries: {}
        };
        if (is_global_mode.value) {
            sql_data.queries[props.initial_request.id] = props.initial_request.sql.queries;
            props.ajax_requests.forEach((request) => {
                if (request.profile && request.profile.sql !== undefined) {
                    sql_data.queries[request.id] = request.profile.sql.queries;
                }
            });
        } else {
            sql_data.queries[props.current_profile.id] = props.current_profile.sql.queries;
        }
        $.each(sql_data.queries, (request_id, data) => {
            // update the total counters
            data.forEach((query) => {
                sql_data.total_requests += 1;
                sql_data.total_duration += parseInt(query['time']);
            });
        });

        return sql_data;
    }

    const sorted_col = ref(is_global_mode.value ? 'request_id' : 'num');
    const sort_dir = ref('asc');
    const sorted_queries_data = computed(() => {
        let sorted = [];

        const sql_data = getCombinedSQLData();
        $.each(sql_data.queries, (request_id, data) => {
            data.forEach((query) => {
                sorted.push({
                    request_id: request_id,
                    num: query['num'],
                    time: query['time'],
                    query: query['query'],
                    rows: query['rows'],
                    warnings: escapeMarkupText(query['warnings']),
                    errors: escapeMarkupText(query['errors']),
                });
            });
        });

        // Filter by current profile id
        if (!is_global_mode.value) {
            sorted = sorted.filter((query) => {
                return query.request_id === props.current_profile.id;
            });
        }

        // Sort by column
        sorted.sort((a, b) => {
            let a_val = a[sorted_col.value];
            let b_val = b[sorted_col.value];
            if (sorted_col.value === 'time') {
                a_val = parseFloat(a_val);
                b_val = parseFloat(b_val);
            }
            if (a_val === b_val) {
                return 0;
            }
            if (sort_dir.value === 'asc') {
                return a_val < b_val ? -1 : 1;
            } else {
                return a_val > b_val ? -1 : 1;
            }
        });
        return sorted;
    });

    function setSortedCol(col) {
        if (sorted_col.value === col) {
            if (sort_dir.value === 'asc') {
                sort_dir.value = 'desc';
            } else {
                sort_dir.value = 'asc';
            }
        } else {
            sorted_col.value = col;
            sort_dir.value = 'asc';
        }
    }
    function copyToClipboard(e) {
        // copy content of code block in clipboard
        const code =  $(e.currentTarget).parent().find('code');
        copyTextToClipboard(code.text());

        // change temporary the button icon to a check then after a while return to the original icon
        const icon = $(e.currentTarget).find('i');
        icon.removeClass('ti-clipboard-copy').addClass('ti-check');
        setTimeout(() => {
            icon.removeClass('ti-check').addClass('ti-clipboard-copy');
        }, 1000);
    }
</script>

<template>
    <div class="overflow-auto py-2 px-3">
        <h2 class="mb-3"></h2>
        <table id="debug-sql-request-table" class="table card-table">
            <thead>
                <tr>
                    <th v-if="is_global_mode" @click="setSortedCol('request_id')">Request ID</th>
                    <th @click="setSortedCol('num')">Number</th>
                    <th @click="setSortedCol('query')">Query</th>
                    <th @click="setSortedCol('time')">Time</th>
                    <th @click="setSortedCol('rows')">Rows</th>
                    <th @click="setSortedCol('warnings')">Warnings</th>
                    <th @click="setSortedCol('errors')">Errors</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="query in sorted_queries_data" :key="query.request_id + '-' + query.num">
                    <td v-if="is_global_mode"><button class="btn btn-link request-link">{{ query.request_id }}</button></td>
                    <td>{{ query.num }}</td>
                    <td>
                        <div class="d-flex align-items-start" style="max-width: 50vw;">
                            <div style="max-width: 50vw; white-space: break-spaces;" class="w-100">
                                <code class="d-block cm-s-default border-0" v-html="query.query"></code>
                            </div>
                            <button type="button" @click="copyToClipboard($event)" class="ms-1 copy-code btn btn-sm btn-ghost-secondary" title="Copy query to clipboard">
                                <i class="ti ti-clipboard-copy"></i>
                            </button>
                        </div>
                    </td>
                    <td>{{ query.time }} ms</td>
                    <td>{{ query.rows }}</td>
                    <td>{{ query.warnings }}</td>
                    <td>{{ query.errors }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<style scoped>
    #debug-sql-request-table thead tr th {
        cursor: pointer;
    }
    #debug-sql-request-table tbody tr td:nth-of-type(3) {
        max-width: 50vw;
        white-space: break-spaces;
    }
    #debug-sql-request-table tbody tr td:nth-of-type(4) {
        white-space: nowrap;
    }
</style>
