<template>
    <div>
        <p>{{ errorMessage }}</p>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            form: {
                username: "",
                password: ""
            },
            errors: [],
            success: false
        };
    },
    methods: {
        onSubmit: function(e) {
            formdata = new FormData();
            formdata.append("username", this.form.username);
            formdata.append("password", this.form.password);
            console.log(e.target.action);
            console.log(formdata);
            axios.get();
            axios
                .post(e.target.action, formdata)
                .then(response => {
                    this.errors = [];
                    this.form.name = "";
                    this.form.description = "";
                    this.success = true;
                })
                .catch(error => {
                    this.errors = error.response.data.errors;
                    this.success = false;
                    console.log(error);
                });
        }
    }
};
</script>
