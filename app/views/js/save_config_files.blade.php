<script type="text/javascript">

    function generate_config_data() {
        return {
            is_saved: false,
            message: '',
            generateData() {

                document.getElementById("submitGenerator").disabled = true;
                document.getElementById("submitGenerator").classList.add('cursor-not-allowed');
                document.getElementById("generatorLoading").classList.remove('hidden');

                const formEntries = new FormData(document.getElementById('serializeConfigData')).entries();
                const json = Object.assign(...Array.from(formEntries, ([x,y]) => ({[x]:y})));

                axios.post('/generate_data', JSON.stringify(json), {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                } ).then((response) => {

                   if (response.data.status === true) {
                       document.getElementById("submitGenerator").disabled = false;
                       document.getElementById("submitGenerator").classList.remove('cursor-not-allowed');
                       document.getElementById("generatorLoading").classList.add('hidden');
                       //window.location.href = response.data.paymentUrl;
                       window.location.href = response.data.downloadZip;
                       //console.log(response.data)
                    }

                }).catch(error => {

                    document.getElementById("migration-config-headline").classList.add("text-red-500");
                    document.getElementById("outputMessage").classList.add("block");
                    document.getElementById("outputMessage").classList.remove("hidden");
                    document.getElementById("showMessage").innerHTML = error.response.data.data;

                    document.getElementById("submitGenerator").disabled = false;
                    document.getElementById("submitGenerator").classList.remove('cursor-not-allowed');
                    document.getElementById("generatorLoading").classList.add('hidden');

                    setTimeout(() => {
                        document.getElementById("migration-config-headline").classList.remove("text-red-500");
                        document.getElementById("outputMessage").classList.add("hidden");
                        document.getElementById("outputMessage").classList.remove('block')
                        document.getElementById("showMessage").innerHTML = "";
                    }, 3000)

                })
            }
        }
    }

    /**
     * One checkbox to rule them all.
     * Checkmark all inputs from specific id (input id="{checkboxInputID}"
     *
     * @param checkboxInputID
     * @constructor
     */
    function CheckedAll(checkboxInputID){

        let checkbox = document.querySelectorAll('input[id^="'+ checkboxInputID +'"]')

        if (document.getElementById('checkAll_'+ checkboxInputID).checked) {
            for(let i=0; i < checkbox.length; i++) {
                if (checkbox[i] !== null) {
                    checkbox[i].checked = true;
                }
            }
        } else {
            for(let i=0; i < checkbox.length; i++) {
                if (checkbox[i] !== null) {
                    checkbox[i].checked = false;
                }
            }
        }

    }

    /**
     * Add custom input field to ignore
     *
     * @param containerID
     * @returns {Promise<void>}
     */
    async function newCheckbox(containerID) {

        let inputName = document.getElementById('ignore-new-field');

        if (inputName.value.length === 0) {
            borderError(inputName)
        } else {
            await axios.post('/new-input-field', { "name": inputName.value }, {
                headers: {
                    'Content-Type': 'application/json'
                }
            }).then((response) => {

                if (response.data.success === true) {
                    let dataHTML = document.getElementById('split-'+containerID);
                    // append data below dataHTML
                    dataHTML.insertAdjacentHTML("afterend", response.data.inputData);
                    inputName.value = "";
                }

            }).catch(() =>{
                inputName.value = inputName.value.replace(/\s/g, '') // clear empty spaces
                borderError(inputName)
            })
        }
    }

    function borderError(inputName) {
        inputName.classList.add('border-red-500')
        setTimeout( () => inputName.classList.remove('border-red-500') , 1800 )
    }

</script>
