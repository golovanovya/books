<template>
    <v-card>
        <v-card-title>
            <v-text-field
                v-model="search"
                @keyup.enter="searchBook"
                append-icon="mdi-magnify"
                label="Search"
                single-line
                hide-details
            ></v-text-field>
        </v-card-title>
        <v-data-table
            :headers="headers"
            :items="books"
            :loading="loading"
            class="elevation-1"
            :options.sync="options"
            :server-items-length="length"
            :footer-props="footer"
        >
            <template v-slot:item.description="{item}">
                <h4>{{item.title}}</h4>
                <h5>{{item.isbn}}</h5>
                <div>{{item.description}}</div>
            </template>
            <template v-slot:item.imageUrl="{item}">
                <img v-if="item.imageUrl !== null" :src="item.imageUrl" :alt="item.title" />
                <div v-else>No cover</div>
            </template>
        </v-data-table>
    </v-card>
</template>

<script>
export default {
    name: "BooksDataTable",
    data () {
        return {
            headers: [
                {
                    text: 'Description',
                    value: 'description',
                    name: 'description',
                    sortable: false,
                },
                {
                    text: 'Cover',
                    value: 'imageUrl',
                    name: 'imageUrl',
                    sortable: false,
                }
            ],
            books: [],
            loading: true,
            options: {},
            search: '',
            length: 0,
            footer: {
                itemsPerPageOptions: [],
            },
            source: null,
            dialog: false
        }
    },
    watch: {
        options: {
            async handler () {
                await this.fetch();
            }
        }
    },
    methods: {
        async fetch() {
            this.loading = true;
            try {
                if (this.source) {
                    this.source.cancel('Operation canceled by the user.');
                }
                this.source = axios.CancelToken.source();
                const response = await axios.get(
                    '/api/books',
                    {
                        params: {
                            page: this.options.page,
                            search: this.search
                        },
                        cancelToken: this.source.token
                    }
                );
                const data = response.data;
                const meta = data.meta;
                this.books = data.data;
                this.length = meta.total;
                this.options.itemsPerPage = meta.per_page;
            } catch (e) {
                console.error(e)
            } finally {
                this.source = null;
                this.loading = false;
            }
        },
        async searchBook() {
            this.options.page = 1;
            await this.fetch();
        }
    }
}
</script>
