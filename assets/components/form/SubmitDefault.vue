<template>
    <button
        @click="e => $emit('click', e)"
        class="font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        type="submit"
        :class="extraClass"
    >
        <slot></slot>
    </button>
</template>

<script>
    export default {
      props: ['type'],
      computed: {
        extraClass () {
          switch (this.type) {
            case 'cancel':
              return 'bg-white text-red-500'
            case 'neutral':
              return 'bg-white text-blue-600 border border-blue-500 hover:shadow-md'
            default:
              return 'bg-blue-500 hover:bg-blue-700 text-white'
          }
        }
      }
    }
</script>
