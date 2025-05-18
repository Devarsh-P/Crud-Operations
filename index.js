src="https://code.jquery.com/jquery-3.7.1.js"
integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
crossorigin="anonymous"

let is_state_visible;
let is_city_visible;

function SelectState(update_state_id) {
    const country_id = document.getElementById('country').value;
    if (!country_id) {
        document.getElementById('state').innerHTML = "<option value=''>Select your state</option>";
        document.getElementById('city').innerHTML = "<option value=''>Select your state</option>";
        return;
    }
    // if (!country_id) return;
    // debugger;
    
    const xhr = new XMLHttpRequest();
    xhr.open("POST", "fetch_state.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
    xhr.onload = function() {
        if (xhr.status === 200) {
            document.getElementById('state-row').style.visibility = 'visible';
            document.getElementById('state').innerHTML = xhr.responseText;
            is_state_visible = true;
            if (document.getElementById('state').value == "") {
                document.getElementById('city').innerHTML = "<option value=''>Select your state</option>";
            }
        } else {
            console.error("Error fetching states.");
        }
    };
    
    xhr.send("country_id=" + encodeURIComponent(country_id)+ 
    "&update_state_id=" + encodeURIComponent(update_state_id));
    return update_state_id;
    
}

function SelectCity(update_city_id, update_state_id) {
    // debugger;
    let state_id;
    if (update_state_id === undefined) {
        state_id = document.getElementById('state').value;
    }
    else{
        state_id = update_state_id;
    }
    // if(!state) return;

    const xhr_city = new XMLHttpRequest();
    xhr_city.open("POST","fetch_city.php",true);
    xhr_city.setRequestHeader("Content-Type","application/x-www-form-urlencoded");

    xhr_city.onload = function() {
        if (xhr_city.status == 200) {
            document.getElementById('city-row').style.visibility = 'visible';
            document.getElementById('address-row').style.visibility = 'visible';
            document.getElementById('city').innerHTML = xhr_city.responseText;
            is_city_visible = true;
        }
        else{
            console.error("Error fetching cities.");
        }
    };
    xhr_city.send("state_id=" + encodeURIComponent(state_id)+ 
    "&update_city_id=" + encodeURIComponent(update_city_id));
}



//Submit the values logic----------------------------------------------------------------------------
function Submit_Update(update_id,deleted_images){
    const formData = new FormData();
    formData.append('first_name', document.getElementById('first_name').value);
    formData.append('last_name', document.getElementById('last_name').value);
    formData.append('mobile', document.getElementById('mobile').value);
    formData.append('dob', document.getElementById('dob').value);
    formData.append('country_id', document.getElementById('country').value);
    formData.append('state_id', document.getElementById('state').value);
    formData.append('city_id', document.getElementById('city').value);
    formData.append('address', document.getElementById('address').value);
    // formData.append('image', document.getElementById('image[]').files); // Append the file object
    const files = document.getElementById('image').files;
    for (let i = 0; i < files.length; i++) {
        formData.append('image[]', files[i]);
    }
    formData.append('state_visibility', is_state_visible);
    formData.append('city_visibility', is_city_visible);
    if (deleted_images !== undefined) {
        deleted_images.forEach(image => {
            formData.append('deleted_images[]', image);
        });
    }
    
    // debugger;
    {// Dependency-based data construction
    // const filteredData = new URLSearchParams ({}).toString();
    
    // if (data.country !== "") { // Add state and city only if country is provided
    //     filteredData.country = data.country;
    //     if (data.state !== "") {
    //         filteredData.state = data.state;
    //     }
    //     if (data.city !== "") {
    //         filteredData.city = data.city;
    //     }
    // }

    // // Always include non-dependent fields
    // ['first_name', 'last_name', 'mobile', 'dob', 'address'].forEach(key => {
    //     if (data[key]) {
    //         filteredData[key] = data[key].trim();
    //     }
    // });

    // // Convert to query string
    // const params = new URLSearchParams(filteredData).toString();
    }
    
    
    xhr_record = new XMLHttpRequest();
    xhr_record.open("POST","record_entries.php", true);
    // xhr_record.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
    
    xhr_record.onload = function () {
        const error_elements = Array.from(document.getElementsByClassName('error-message'));
        if(xhr_record.status == 200){
            const response = JSON.parse(xhr_record.responseText);
            try {
                // const response = JSON.parse(xhr_record.responseText);
                if (response.status === 'success') {
                    console.log("Values Inserted Successfully");
                }
            } catch (err) {
                console.error("Invalid JSON response:", err);
            }
            if (response.status === 'success') {
                console.log("Values Inserted Successfully");
                
                const value_elements = Array.from(document.getElementsByClassName('form-control'));
                document.getElementById('state-row').style.visibility = 'hidden';
                document.getElementById('city-row').style.visibility = 'hidden';
                document.getElementById('address-row').style.visibility = 'hidden';

                is_state_visible = false;
                is_city_visible = false;


                if (error_elements.length !== 0) {
                    error_elements.forEach(element => {
                        element.style.visibility = 'hidden';
                    });
                }
                
                value_elements.forEach(element => {
                    element.value = "";
                });
                window.location.href = "home.php?page-num=1";
            }
            else if(response.status === 'error'){
                if (error_elements.length !== 0) {
                    error_elements.forEach(element => {
                        element.innerHTML = "";
                    });
                }
                for (const key in response.error){
                    const element = document.getElementById(`${key}_error`);
                    // element.style.visibility = 'visible';
                    element.innerText = response.error[key];
                }
            }
        }
    }
    if (update_id !== undefined) {
        formData.append('update_id', update_id);
    }
    xhr_record.send(formData);

    // if (update_id !== undefined) {
    //     xhr_record.send(formData+ 
    //     "&update_id=" + encodeURIComponent(update_id));
    // }
    // else{
    // }

}


// $('#home').on('click', function() {
//     window.location.href = 'home.php';
// });

// $(document).ready(function () {
//     $('submit').on('click', function (event) {
//         event.preventDefault();
        
//         const params = new URLSearchParams({
//             first_name: $('#first_name').val().trim(),
//             last_name: $('#last_name').val().trim(),
//             mobile: $('#mobile').val().trim(),
//             dob: $('#dob').val().trim(),
//             country: $('#country').val().trim(),
//             state:$('#state').val().trim(),
//             city: $('#city').val().trim(),
//             address: $('#address').val().trim(),
//             state_visibility: is_state_visible,
//             city_visibility: is_city_visible
//         }).toString();
        
//         $.ajax({
//             url
//         });


//     });//Function over
// });
