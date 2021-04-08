<template>
    <section>
        <h1 class="text-3xl">JWT PAGE</h1>
        <form @submit.prevent>
            <div class="flex w-full">
              <div class="w-1/2 p-2">
                <TextareaDefault
                    v-model="token"
                    @change="decodeToken"
                    @input="decodeToken"
                    label="Token"
                />
                <SelectDefault
                    id="selectSecret"
                    label="Choose secret"
                    @change="payload => secret = payload"
                    :options="secretsOptions"
                />
                <InputDefault
                    label="Secret"
                    v-model="secret"
                    placeholder="xxxxxx"
                    type="text"
                    name="secret"
                />
                <div v-if="!toggleSecret">
                  <SubmitDefault @click="createJwt" class="mt-4">Create</SubmitDefault>
                  <SubmitDefault @click="toggleSecret = !toggleSecret" class="mt-4" type="neutral">Add secret</SubmitDefault>
                </div>
                <div v-else>
                  <hr class="my-4">
                  <h3 class="text-xl text-gray-600">New secret creation</h3>
                  <div v-if="newSecret.message" class="bg-green-400 text-white p-2 rounded shadow-md">
                    {{ newSecret.message }}
                  </div>
                  <InputDefault
                      label="Label"
                      v-model="newSecret.label"
                      placeholder="name of secret"
                      type="text"
                      name="secret"
                  />
                  <InputDefault
                      label="Secret"
                      v-model="newSecret.secret"
                      placeholder="xxxxxx"
                      type="text"
                      name="secret"
                  />
                  <SubmitDefault @click="addSecret" class="mt-4">Add</SubmitDefault>
                  <SubmitDefault @click="toggleSecret = !toggleSecret" class="mt-4" type="cancel">Cancel</SubmitDefault>
                </div>

                <hr class="mt-20">
                <div v-if="newToken">
                  <label class="block text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4" >New Token</label>
                  <div v-if="copiedMessage" class="text-green-600 text-xl">{{ copiedMessage }}</div>
                  <div
                      @click="copyToClipboard"
                      class="cursor-pointer overflow-hidden bg-gray-300 shadow-lg p-3 rounded"
                  >
                    {{ newToken }}
                    <input type="hidden" id="token" :value="newToken">
                  </div>
                </div>
              </div>
              <div class="w-1/2 p-2">
                <label class="block text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4" >Decoded</label>
                <v-jsoneditor
                    v-model="json"
                    :options="{'mode': 'code'}"
                    :plus="false"
                    height="900px"
                />
              </div>
            </div>
        </form>
    </section>
</template>

<script>
    import TextareaDefault from "../form/TextareaDefault.vue";
    import InputDefault from "../form/InputDefault.vue";
    import SubmitDefault from "../form/SubmitDefault.vue";
    import SelectDefault from "../form/SelectDefault.vue";
    import VJsoneditor from 'v-jsoneditor/src/index'
    import jwt_decode from "jwt-decode";
    import { KJUR } from 'jsrsasign'
    import axios from 'axios';

    export default {
      components: { TextareaDefault, InputDefault, SubmitDefault, VJsoneditor, SelectDefault },
      async created() {
        const res = await axios.get('/jwt/secrets')
        this.secretsOptions = res.data
      },
      data () {
        return {
          token: '',
          secret: null,
          copiedMessage: null,
          json: {
          },
          newToken: null,
          secretsOptions: [],
          newSecret: {
            label: '',
            secret: '',
            message: null
          },
          toggleSecret: false
        }
      },
      methods: {
        async addSecret () {
          await axios.post(
              '/jwt/secret',
              {
                label: this.newSecret.label,
                secret: this.newSecret.secret
              }
            )

          this.secretsOptions.push({ label: this.newSecret.label, secret: this.newSecret.secret })
          this.newSecret.message = 'Creation success'
          setTimeout(() => {
            this.newSecret.message = null
          }, 5000)
        },
        decodeToken () {
          try {
            this.json = jwt_decode(this.token)
          } catch (e) {
            //
          }
        },
        async createJwt () {
          const header = {
            "typ": "JWT",
            "alg": "HS512"
          }

          this.json.exp = Math.round((Date.now() / 1000) + 3600)

          this.newToken = KJUR.jws.JWS.sign(
              "HS512",
              JSON.stringify(header),
              JSON.stringify(this.json),
              {rstr: this.secret}
          );
        },
        copyToClipboard () {
          let testingCodeToCopy = document.querySelector('#token')
          testingCodeToCopy.setAttribute('type', 'text')    // 不是 hidden 才能複製
          testingCodeToCopy.select()

          document.execCommand('copy');

          this.displayMessage('Copied to clipboard')
          testingCodeToCopy.setAttribute('type', 'hidden')
          window.getSelection().removeAllRanges()
        },
        displayMessage (message) {
          this.copiedMessage = message
          setTimeout(() => {
            this.copiedMessage = null
          }, 5000)
        }
      }
    }
</script>
