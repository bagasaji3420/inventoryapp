/**
 *  Page auth register multi-steps
 */

"use strict";

// Multi Steps Validation
// --------------------------------------------------------------------
document.addEventListener("DOMContentLoaded", function (e) {
    (function () {
        const stepsValidation = document.querySelector("#multiStepsValidation");
        if (typeof stepsValidation !== undefined && stepsValidation !== null) {
            // Multi Steps form
            const stepsValidationForm =
                stepsValidation.querySelector("#multiStepsForm");
            // Form steps
            const stepsValidationFormStep1 = stepsValidationForm.querySelector(
                "#accountDetailsValidation",
            );
            const stepsValidationFormStep2 = stepsValidationForm.querySelector(
                "#personalInfoValidation",
            );
            // Multi steps next prev button
            const stepsValidationNext = [].slice.call(
                stepsValidationForm.querySelectorAll(".btn-next"),
            );
            const stepsValidationPrev = [].slice.call(
                stepsValidationForm.querySelectorAll(".btn-prev"),
            );

            // GANTI JADI (hanya mobile & pincode)
            const multiStepsMobile = document.querySelector(
                ".multi-steps-mobile",
            );
            const multiStepsPincode = document.querySelector(
                ".multi-steps-pincode",
            );

            // Pincode
            if (multiStepsPincode) {
                multiStepsPincode.addEventListener("input", (event) => {
                    multiStepsPincode.value = formatNumeral(
                        event.target.value,
                        {
                            delimiter: "",
                            numeral: true,
                        },
                    );
                });
            }

            let validationStepper = new Stepper(stepsValidation, {
                linear: true,
            });

            // Account details
            const multiSteps1 = FormValidation.formValidation(
                stepsValidationFormStep1,
                {
                    fields: {
                        multiStepsUsername: {
                            validators: {
                                notEmpty: {
                                    message: "Please enter username",
                                },
                                stringLength: {
                                    min: 6,
                                    max: 30,
                                    message:
                                        "The name must be more than 6 and less than 30 characters long",
                                },
                                regexp: {
                                    regexp: /^[a-zA-Z0-9 ]+$/,
                                    message:
                                        "The name can only consist of alphabetical, number and space",
                                },
                            },
                        },
                        multiStepsEmail: {
                            validators: {
                                notEmpty: {
                                    message: "Please enter email address",
                                },
                                emailAddress: {
                                    message:
                                        "The value is not a valid email address",
                                },
                            },
                        },
                        multiStepsPass: {
                            validators: {
                                notEmpty: {
                                    message: "Please enter password",
                                },
                            },
                        },
                        multiStepsConfirmPass: {
                            validators: {
                                notEmpty: {
                                    message: "Confirm Password is required",
                                },
                                identical: {
                                    compare: function () {
                                        return stepsValidationFormStep1.querySelector(
                                            '[name="multiStepsPass"]',
                                        ).value;
                                    },
                                    message:
                                        "The password and its confirm are not the same",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            // Use this for enabling/changing valid/invalid class
                            // eleInvalidClass: '',
                            eleValidClass: "",
                            rowSelector: ".form-control-validation",
                        }),
                        autoFocus: new FormValidation.plugins.AutoFocus(),
                    },
                },
            );

            // Personal info
            const multiSteps2 = FormValidation.formValidation(
                stepsValidationFormStep2,
                {
                    fields: {
                        multiStepsFirstName: {
                            validators: {
                                notEmpty: {
                                    message: "Please enter first name",
                                },
                            },
                        },
                        multiStepsAddress: {
                            validators: {
                                notEmpty: {
                                    message: "Please enter your address",
                                },
                            },
                        },
                    },
                    plugins: {
                        trigger: new FormValidation.plugins.Trigger(),
                        bootstrap5: new FormValidation.plugins.Bootstrap5({
                            // Use this for enabling/changing valid/invalid class
                            // eleInvalidClass: '',
                            eleValidClass: "",
                            rowSelector: function (field, ele) {
                                // field is the field name
                                // ele is the field element
                                switch (field) {
                                    case "multiStepsFirstName":
                                    case "multiStepsAddress":
                                        return ".form-control-validation";
                                    default:
                                        return ".row";
                                }
                            },
                        }),
                        autoFocus: new FormValidation.plugins.AutoFocus(),
                    },
                },
            ).on("core.form.valid", function () {
                stepsValidationForm.submit(); // ← JADI INI
            });

            stepsValidationNext.forEach((item) => {
                item.addEventListener("click", (event) => {
                    event.preventDefault(); // tambah ini
                    if (item.classList.contains("btn-submit")) return;
                    switch (validationStepper._currentIndex) {
                        case 0:
                            multiSteps1.validate().then(function (status) {
                                if (status === "Valid") {
                                    validationStepper.next();
                                }
                            });
                            break;
                        case 1:
                            multiSteps2.validate();
                            break;
                        default:
                            break;
                    }
                });
            });

            stepsValidationPrev.forEach((item) => {
                item.addEventListener("click", (event) => {
                    switch (validationStepper._currentIndex) {
                        case 1:
                            validationStepper.previous();
                            break;

                        case 0:

                        default:
                            break;
                    }
                });
            });

            const btnSubmit = document.querySelector(".btn-submit");
            if (btnSubmit) {
                btnSubmit.addEventListener("click", function () {
                    multiSteps2.validate();
                });
            }
        }
    })();
});
