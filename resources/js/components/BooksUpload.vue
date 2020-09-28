<template>
    <v-card>
        <v-card-title class="headline grey lighten-2">
            Upload file
        </v-card-title>

        <v-card-text>
            <v-file-input
                accept="xml"
                :disabled="disabled"
                label="File input"
                v-model="file"
            ></v-file-input>
        </v-card-text>
        <v-card-text>
            <v-alert
                v-for="error in errors.file"
                class="red--text"
            >
                {{error}}
            </v-alert>
        </v-card-text>

        <v-divider></v-divider>

        <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
                color="primary"
                text
                :disabled="disabled"
                @click="upload"
            >
                Upload
            </v-btn>
        </v-card-actions>
    </v-card>
</template>

<script>
export default {
    name: "BooksUpload",
    data () {
        return {
            disabled: false,
            file: null,
            progress: 0,
            source: null,
            errors: {}
        };
    },
    methods: {
        async upload() {
            this.errors = {};
            if (!this.file) {
                this.errors = {file: ['Please select file']};
                return false;
            }
            this.disabled = true;
            try {
                const formData = new FormData();
                formData.append('file', this.file);
                const response = await axios.post(
                        '/api/books/upload',
                        formData,
                        {
                            headers: {
                                "Content-Type": "multipart/form-data"
                            }
                        }
                    );
                console.log(response);
                alert('File uploaded successfully');
            } catch (e) {
                if (e.response && e.response.data.error && e.response.data.error) {
                    this.errors = e.response.data.error;
                } else {
                    alert('Error please try again later');
                }
            } finally {
                this.disabled = false;
            }
        }
    }
};
</script>
