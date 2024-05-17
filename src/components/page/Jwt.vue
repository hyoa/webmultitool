<template>
  <section>
    <div class="flex w-full">
      <div class="w-1/2 p-2">
        <div>
          <h3>Token raw</h3>
          <textarea
              class="raw-token bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
              v-model="token"
          ></textarea>
        </div>
      </div>
      <div class="w-1/2 p-2 h-80">
        <h3>Token decoded</h3>
        <Vue3JsonEditor
          v-model="json"
          :expandedOnStart="true"
          @json-change="json = $event"
          mode="code"
        />
      </div>
    </div>
  </section>
  <section class="p-2" v-if="!updateSecret">
    <hr class="mb-6">
    <h3>Forge</h3>
    <div>
      <label for="secret" class="block text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4">secret</label>
      <!-- <input
          id="secret"
          class="bg-gray-200 appearance-none border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500"
          v-model="secret"
        > -->
      <select v-model="secret" class="block border-2 border-gray-200 rounded w-full py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500">
        <option v-for="secret in secrets" :value="secret.value">{{ secret.name }}</option>
      </select>

      <div class="flex">
        <button
          class="font-bold mt-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline bg-blue-500 text-white"
          type="submit"
          @click="forge"
        >
          Forge
        </button>
        <button
          class="font-bold mt-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline"
          @click="updateSecret = true"
        >
          update secret
        </button>
      </div>

            
      <div
        class="cursor-pointer overflow-scroll bg-gray-300 shadow-lg p-3 rounded mt-2"
        @click="copyToClipboard"
        v-if="newToken">
        {{ newToken }}
      </div>
      <div v-if="copiedMessage" class="text-green-600 text-xl">{{ copiedMessage }}</div>
      <input type="hidden" id="token" :value="newToken">
    </div>

  </section>
  <section class="p-2" v-if="updateSecret">
    <hr class="mb-6">
    <h3>Update secrets</h3>
    <fieldset class="border p-2">
      <legend>Secrets</legend>
      <div
        class="flex"
        v-for="(secret, index) in secrets"
      >
        <input class="appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" type="text" v-model="secrets[index].name">
        <input class="appearance-none border-2 border-gray-200 rounded py-2 px-4 text-gray-700 leading-tight focus:outline-none focus:bg-white focus:border-purple-500" type="text" v-model="secrets[index].value">
      </div>
      <div>
        <button
          class="font-bold mt-4 py-1 px-2 rounded focus:outline-none focus:shadow-outline bg-blue-500 text-white"
          @click="addSecret"
        >
          add
        </button>
      </div>
    </fieldset>
    <div class="flex">
      <button
        class="font-bold mt-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline bg-green-700 text-white"
        @click="saveSecrets"
      >
        save
      </button>
      <button
        class="font-bold mt-4 py-2 px-4 rounded focus:outline-none focus:shadow-outline text-red-500"
        @click="updateSecret = false"
      >
        cancel
      </button>
    </div>
  </section>
</template>

<script>
import { Vue3JsonEditor } from 'vue3-json-editor'
import { jwtDecode } from "jwt-decode"
import { KJUR } from 'jsrsasign'
import * as CryptoJS from 'crypto-js'

export default {
  name: 'Jwt',
  components: {
    Vue3JsonEditor
  },
  created () {
    let secretsEncrypted = JSON.parse(localStorage.getItem('secrets'))

    if (secretsEncrypted) {
      this.secrets = secretsEncrypted.map(secret => {
        return {
          name: secret.name,
          value: CryptoJS.AES.decrypt(secret.value, 'secret').toString(CryptoJS.enc.Utf8)
        }
      })
    }
  },
  data() {
    return {
      token: '',
      secret: '',
      secrets: [],
      decodedJson: {},
      json: {},
      newToken: '',
      copiedMessage: null,
      updateSecret: false
    }
  },
  watch: {
    token: function (newToken) {
      this.decodedJson = jwtDecode(newToken)
      this.json = jwtDecode(newToken)
    }
  },
  methods: {
    forge() {
      let header = {
        "alg": "HS512",
        "typ": "JWT"
      }

      console.log(this.secret)

      this.json.exp = Math.round((Date.now() / 1000) + 3600)


      this.newToken = KJUR.jws.JWS.sign(
          "HS512",
          JSON.stringify(header),
          JSON.stringify(this.json),
          {rstr: this.secret}
      );

    },
    copyToClipboard () {
      let token = document.querySelector('#token')
      token.setAttribute('type', 'text')
      token.select()

      document.execCommand('copy');

      this.displayMessage('Copied to clipboard')
      token.setAttribute('type', 'hidden')
      window.getSelection().removeAllRanges()
    },
    displayMessage (message) {
      this.copiedMessage = message
      setTimeout(() => {
        this.copiedMessage = null
      }, 5000)
    },
    addSecret() {
      this.secrets.push({
        name: '',
        value: ''
      })
    },
    saveSecrets() {
      let newSecrets = this.secrets.filter(secret => secret.name !== '' && secret.value !== '')
      this.secrets = newSecrets

      let secretsEncrypted = this.secrets.map(secret => {
        return {
          name: secret.name,
          value: CryptoJS.AES.encrypt(secret.value, 'secret').toString()
        }
      })


      localStorage.setItem('secrets', JSON.stringify(secretsEncrypted))
      this.updateSecret = false
    },
    jsonChange(json) {
      this.json = json
    }
  }
}

</script>

<style>
.jsoneditor-vue, .raw-token {
  height: 30vh;
}
</style>