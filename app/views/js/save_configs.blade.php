<script type="text/javascript">
    function save_configuration() {
        return {
            is_saved: false,
            message: '',
            submitData() {

                const formEntries = new FormData(document.getElementById('saveConf')).entries();
                const json = Object.assign(...Array.from(formEntries, ([x,y]) => ({[x]:y})));

                axios.post('/save_configuration', JSON.stringify(json), {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                }).then((response) => {

                    this.is_saved = true
                    this.message = 'Successfully saved!'

                    setTimeout(() => this.is_saved = false, 2500)

                }).catch(() => {
                    this.message = 'Ooops! Something went wrong!'
                })
            }
        }
    }

</script>
