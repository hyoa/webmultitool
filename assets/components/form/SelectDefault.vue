<template>
  <div class="relative">
    <label :for="id" class="block text-gray-500 font-bold md:text-left mb-1 md:mb-0 pr-4" v-if="label">{{ label }} </label>
    <select
        class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        :id="id"
        @change="$emit('change', $event.target.value)"
    >
      <option :value="null">Choose a secret</option>
      <option v-for="{ secret, label } in options" :value="secret">{{ label }}</option>
    </select>
    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700">
      <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"/></svg>
    </div>
  </div>
</template>

<script>
export default {
  props: ['type', 'placeholder', 'value', 'label', 'index', 'name', 'disabled', 'options'],
  computed: {
    id () {
      if (this.index) {
        return `${this.name}_${this.index}`;
      }

      return this.name;
    }
  }
}
</script>
