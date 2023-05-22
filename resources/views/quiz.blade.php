<style>
    /* Style the form */
    #regForm {
        background-color: #ffffff;
        margin: 100px auto;
        padding: 40px;
        width: 70%;
        min-width: 300px;
    }

    /* Style the input fields */
    input {
        padding: 10px;
        width: 100%;
        font-size: 17px;
        font-family: Raleway;
        border: 1px solid #aaaaaa;
    }

    /* Mark input boxes that gets an error on validation: */
    input.invalid {
        background-color: #ffdddd;
    }

    /* Hide all steps by default: */
    .tab {
        display: none;
    }

    /* Make circles that indicate the steps of the form: */
    .step {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbbbbb;
        border: none;
        border-radius: 50%;
        display: inline-block;
        opacity: 0.5;
    }

    /* Mark the active step: */
    .step.active {
        opacity: 1;
    }

    /* Mark the steps that are finished and valid: */
    .step.finish {
        background-color: #04AA6D;
    }
</style>

<x-app-layout>
    <x-slot name="header"> {{ $quiz->title }} </x-slot>
    <div class="card">
        <div class="card-body">
            <form id="regForm" action="{{ route('quiz.result', $quiz->slug) }}" method="POST">

                @csrf
                @foreach ($quiz->questions as $question)
                    <div class="tab">
                        <strong> #{{ $loop->iteration }}.</strong>{{ $question->question }}
                        @if ($question->image)
                            <img src="{{ asset($question->image) }}" alt="" class="img-responsive" style="width: 60%; border-radius:5px">
                        @endif
                        <div class="form-check ml-3 mt-2">
                            <input class="form-check-input" type="radio" name="{{ $question->id }}" id="quiz{{ $question->id }}1" value="answer1">
                            <label class="form-check-label" for="quiz{{ $question->id }}1">
                                {{ $question->answer1 }}
                            </label>
                        </div>
                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" name="{{ $question->id }}" id="quiz{{ $question->id }}2" value="answer2">
                            <label class="form-check-label" for="quiz{{ $question->id }}2">
                                {{ $question->answer2 }}
                            </label>
                        </div>
                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" name="{{ $question->id }}" id="quiz{{ $question->id }}3" value="answer3">
                            <label class="form-check-label" for="quiz{{ $question->id }}3">
                                {{ $question->answer3 }}
                            </label>
                        </div>
                        <div class="form-check ml-3">
                            <input class="form-check-input" type="radio" name="{{ $question->id }}" id="quiz{{ $question->id }}4" value="answer4">
                            <label class="form-check-label" for="quiz{{ $question->id }}4">
                                {{ $question->answer4 }}
                            </label>
                        </div>

                    </div>
                @endforeach

                <div style="overflow:auto;">
                    <div style="float:right;">
                        <button type="button" id="prevBtn" onclick="nextPrev(-1)">Previous</button>
                        <button type="button" id="nextBtn" onclick="nextPrev(1)">Next</button>
                    </div>
                </div>

                <!-- Circles which indicates the steps of the form: -->
                <div style="text-align:center;margin-top:40px;">
                    @foreach ($quiz->questions as $question)
                        <span class="step"></span>
                    @endforeach
                </div>

            </form>

        </div>
    </div>
</x-app-layout>


<script>
    var currentTab = 0; // Current tab is set to be the first tab (0)
    showTab(currentTab); // Display the current tab

    function showTab(n) {
        // This function will display the specified tab of the form ...
        var x = document.getElementsByClassName("tab");
        x[n].style.display = "block";
        // ... and fix the Previous/Next buttons:
        if (n == 0) {
            document.getElementById("prevBtn").style.display = "none";
        } else {
            document.getElementById("prevBtn").style.display = "inline";
        }
        if (n == (x.length - 1)) {
            document.getElementById("nextBtn").innerHTML = "Submit";
        } else {
            document.getElementById("nextBtn").innerHTML = "Next";
        }
        // ... and run a function that displays the correct step indicator:
        fixStepIndicator(n)
    }

    function nextPrev(n) {
        // This function will figure out which tab to display
        var x = document.getElementsByClassName("tab");
        // Exit the function if any field in the current tab is invalid:
        if (n == 1 && !validateForm()) return false;
        // Hide the current tab:
        x[currentTab].style.display = "none";
        // Increase or decrease the current tab by 1:
        currentTab = currentTab + n;
        // if you have reached the end of the form... :
        if (currentTab >= x.length) {
            //...the form gets submitted:
            document.getElementById("regForm").submit();
            return false;
        }
        // Otherwise, display the correct tab:
        showTab(currentTab);
    }

    function validateForm() {

        // This function deals with validation of the form fields
        var x, y, i, valid = true;
        x = document.getElementsByClassName("tab");
        y = x[currentTab].getElementsByTagName("input");
        // A loop that checks every input field in the current tab:
        var arr = [];
        for (i = 0; i < y.length; i++) {

            arr.push(y[i].checked);
            // If a field is empty...
            // if (y[i].checked == true) {
            //     break;

            // } else {
            //     // add an "invalid" class to the field:
            //     y[i].className += " invalid";
            //     // and set the current valid status to false:
            //     valid = false;
            // }
        }
        if (!arr.includes(true)   ) {
            for (i = 0; i < y.length; i++) {
                y[i].className += " invalid";
                valid = false;
            }

        }
        console.log(arr)
        // If the valid status is true, mark the step as finished and valid:
        if (valid) {
            document.getElementsByClassName("step")[currentTab].className += " finish";
        }
        return valid; // return the valid status
    }

    function fixStepIndicator(n) {
        // This function removes the "active" class of all steps...
        var i, x = document.getElementsByClassName("step");
        for (i = 0; i < x.length; i++) {
            x[i].className = x[i].className.replace(" active", "");
        }
        //... and adds the "active" class to the current step:
        x[n].className += " active";
    }
</script>
