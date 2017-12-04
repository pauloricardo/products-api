<template>
    <div :class="currentClass">
        {{message}}
    </div>
</template>
<script>
    export default {
        props: ['currentClass', 'message'],
        mounted() {
            this.$parent.$bus.$on('emitMessage', (response) => {
                if(response) {
                    this.currentClass = 'alert alert-' + response.class;
                    this.message = response.message;
                }
            })
        }
    }
</script>