$(document).ready(function() {
    $('#rsTime').hide(); // Hide the rsTime select element... for now
    $('label[for="rsTime"]').hide(); // Hide the label too

    // Disable submit button on form load
    $('#reservationForm').find('button[type="submit"]').prop('disabled', true).addClass('btn btn-secondary');

    $('#rsDate').change(function(e) {
        let selectedDate = e.target.value;

        $('#rsTime').empty();

        $.ajax({
            url: 'check_availability.php',
            type: 'POST',
            data: {
                selectedDate: selectedDate
            },
            dataType: 'json',
            success: function(response) {
                $('#rsTime').empty();

                let reservedTimes = response.reservedTimes;
                let availableTimes = [];

                for (let hour = 9; hour <= 21; hour++) {
                    for (let minutes = 0; minutes <= 45; minutes += 15) {
                        let adjustedHour = hour;
                        let ampm = adjustedHour >= 12 ? 'PM' : 'AM';

                        if (adjustedHour > 12) {
                            adjustedHour -= 12;
                        } else if (adjustedHour === 0) {
                            adjustedHour = 12;
                        }

                        let formattedTime = `${adjustedHour.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}`;

                        let isReserved = false;

                        for (let i = 0; i < reservedTimes.length; i++) {
                            let reservedHour = parseInt(reservedTimes[i].split(':')[0]);
                            let reservedMinutes = parseInt(reservedTimes[i].split(':')[1]);

                            if (reservedHour === adjustedHour && reservedMinutes === minutes) {
                                isReserved = true;
                                break;
                            }
                        }

                        if (!isReserved) {
                            availableTimes.push(formattedTime);
                        }
                    }
                }

                availableTimes.forEach(time => {
                    let option = $('<option>').val(time).text(time);
                    $('#rsTime').append(option);
                });

                $('#rsTime').show(); // Show the rsTime select element
                $('label[for="rsTime"]').show(); // Don't forget the label
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

    // Validate first name and last name patterns
    $('#firstName, #lastName').on('input', function(e) {
        var input = $(this);
        var value = input.val();
        var pattern = /^[a-zA-Z]+$/; // Only allow alphabetic characters
    
        if (!pattern.test(value)) {
        input.addClass('is-invalid');
        } else {
        input.removeClass('is-invalid');
        }
    
        validateForm();
    });
    
    // Validate email pattern
    $('#guestEmail').on('input', function(e) {
        var input = $(this);
        var value = input.val();
        var pattern = /^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+\.[a-zA-Z0-9-.]+$/; // Email pattern
    
        if (!pattern.test(value)) {
        input.addClass('is-invalid');
        } else {
        input.removeClass('is-invalid');
        }
    
        validateForm();
    });
    
    // Validate other fields for empty values
    $('#rsDate, #rsTime, #rsPartySize, #rsOccasion').on('input', function(e) {
        var input = $(this);
        var value = input.val();
    
        if (value.trim() === '') {
        input.addClass('is-invalid');
        } else {
        input.removeClass('is-invalid');
        }
    
        validateForm();
    });
    
    // Function to validate the form and enable/disable submit button
    function validateForm() {
        var hasErrors = $('#reservationForm').find('.is-invalid').length > 0;
        var hasEmptyFields = $('#rsDate, #rsTime, #rsPartySize, #rsOccasion').filter(function() {
        return $(this).val().trim() === '';
        }).length > 0;
    
        var submitButton = $('#reservationForm').find('button[type="submit"]');
    
        if (hasErrors || hasEmptyFields) {
        submitButton.prop('disabled', true).removeClass('btn btn-primary').addClass('btn btn-secondary');
        } else {
        submitButton.prop('disabled', false).removeClass('btn btn-secondary').addClass('btn btn-primary');
        }
    }
    
    // Trigger input event on form fields to validate on user interaction
    $('#firstName, #lastName, #guestEmail, #rsDate, #rsTime, #rsPartySize, #rsOccasion').on('input', function() {
        $(this).removeClass('is-invalid');
    });
    
    // Reset form errors and send GA event on form submission
    $('#reservationForm').on('submit', function() {
        $(this).find('.is-invalid').removeClass('is-invalid');

        // Google Analytics event for form submission
        if (typeof gtag === 'function') {
            gtag('event', 'reservation_submitted', {
                'event_category': 'Form',
                'event_label': 'Reservation Form'
            });
        } else {
            console.warn("gtag is not available");
        }
    });

});