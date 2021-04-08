<template>
    <div>
        <h1 class="text-3xl">CORS Checker</h1>
        <div>
            <section id="form">
                <form @submit.prevent="validateCors" class="w-full">
                    <div class="flex space-x-4">
                        <InputDefault
                            label="Method"
                            v-model="method"
                            placeholder="GET"
                            type="text"
                            name="method"
                            class="w-1/3"
                        />
                        <InputDefault
                            label="URL"
                            v-model="url"
                            placeholder="https://example.com"
                            type="text"
                            name="url"
                            class="w-2/3"
                        />
                    </div>

                    <SubmitDefault class="mt-4">Send</SubmitDefault>

                    <fieldset class="border my-4 px-4 py-2">
                        <legend class="bg-teal-700 p-2 text-white">Headers</legend>

                        <div
                            class="flex space-x-4 my-2"
                            v-for="(header, index) in headers"
                            :key="index"
                        >
                            <InputDefault
                                v-model="header.name"
                                placeholder="Authorization"
                                type="text"
                                :index="index"
                                name="headername"
                                class="w-1/3"
                            />

                            <InputDefault
                                v-model="header.value"
                                placeholder="Bearer xxxxxxxx"
                                type="text"
                                :index="index"
                                name="headervalue"
                                class="w-2/3"
                            />
                        </div>

                        <button
                            class="bg-teal-600 text-white px-2 py-1 rounded-md mt-2"
                            @click.prevent="addHeaderField"
                        >
                            Add a header
                        </button>
                    </fieldset>
                </form>
            </section>
            <section v-if="error !== null" id="result">
                <div v-if="error" class="bg-red-500 py-4 px-3 text-white rounded-lg shadow-md">
                    Preflight failed
                </div>
                <div v-else class="bg-green-500 py-4 px-3 text-white rounded-lg shadow-md">
                    Preflight succeeded
                </div>
            </section>
        </div>
    </div>
</template>

<script>
    import InputDefault from "../form/InputDefault.vue";
    import SubmitDefault from "../form/SubmitDefault.vue";
    import axios from 'axios';

    export default {
        components: { InputDefault, SubmitDefault },
        data () {
          return {
            url: '',
            method: 'GET',
            headers: [],
            error: null
          }
        },
        methods: {
          addHeaderField () {
            this.headers.push({ name: '', value: '' });
          },
          async validateCors () {
            this.error = null;
            const headers = {};

            for (let header of this.headers) {
              if (header.name !== '' && header.value !== '') {
                headers[header.name] = header.value;
              }
            }

            try {
              await axios.request({
                method: this.method,
                url: this.url,
                headers
              });

            } catch (e) {
              if (e.response === undefined) {
                this.error = true;
                return;
              }
            }

            this.error = false;
          }
        }
    }
</script>
