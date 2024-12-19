

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
   <style>
        fieldset {
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
        }

        legend {
            width: auto;
            padding: 0 10px;
            font-size: 1.1rem;
            font-weight: bold;
            border-radius: 25px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-12 text-center mb-4">
               
                <h2 id="viewName"></h2>
                <img id="randomImage" src="" alt="Image aléatoire" style="display:none; width: 100px; height:100px;">

            </div>
        </div>

        <div class="row no-gutters flex-nowrap justify-content-center">
            <!-- Catégorie -->
            <div class="col-md-5 m-4">
                <form id="categoryForm">
                    <fieldset>
                        <legend class="bg-primary text-white text-left">Catégorie</legend>
                        <div id="categoryOptions"></div>
                    </fieldset>
                </form>
            </div>

            <!-- Description -->
            <div class="col-md-5 m-4">
                <form id="descriptionForm">
                    <fieldset>
                        <legend class="bg-primary text-white text-left">Description</legend>
                        <div id="descriptionOptions"></div>
                    </fieldset>
                </form>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-md-4 w-25 text-center">
                <button type="button" id="nextBtn" class="btn btn-primary btn-block">Suivant</button>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
            $(document).ready(function() {
                let name = localStorage.getItem('name');
                if (name) {
                    document.getElementById('viewName').innerText = 'Bonjour, ' + name + '!';
                } else {
                    document.getElementById('viewName').innerText = 'Nom non trouvé.';
                }

                let images = []; 
                let currentIndex = 0; 
                let score = 0; 

                $.ajax({
                    url: 'http://localhost/TP2/data.php/?data=images', 
                    method: 'GET',
                    success: function(data) {
                        if (data.images && Array.isArray(data.images)) {
                            images = shuffle(data.images).slice(0, 10);
                            console.log('Images sélectionnées:', images);
                            loadQuestion(images[currentIndex]);
                        } else {
                            console.error("Erreur : Impossible de récupérer les images.");
                            alert("Erreur lors du chargement des images.");
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Erreur AJAX:', error);
                    }
                });

                function loadQuestion(image) {
                    $('#randomImage').attr('src', '../data/' + image).show();

                    $.ajax({
                        url: `http://localhost/TP2/data.php/?data=response&image=${image}`,
                        method: 'GET',
                        success: function(response) {
                            if (response.category && response.description) {
                                console.log(`Image : ${image}`);
                                console.log(`Catégorie : ${response.category}`);
                                console.log(`Description : ${response.description}`);

                                loadOptions('categories', response.category, '#categoryOptions', response.category, response.description); // Catégorie correcte et Description correcte
                                loadOptions('propositions', response.description, '#descriptionOptions', response.category, response.description); // Description correcte
                            } else {
                                console.error(`Erreur : Données incorrectes pour l'image ${image}`);
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur lors de la récupération des informations:', error);
                        }
                    });
                }

                function loadOptions(endpoint, correctValue, targetElement, correctCategory, correctDescription) {
                    $.ajax({
                        url: `http://localhost/TP2/data.php/?data=${endpoint}`,
                        method: 'GET',
                        success: function(data) {
                            var options = Array.from(new Set(data[endpoint])).filter(function(option) {
                                return option !== correctValue;
                            });

                            options = options.slice(0, 4);
                            options.push(correctValue);
                            options = shuffle(options);

                            var optionsHtml = '';
                            options.forEach(function(option) {
                                optionsHtml += `<label><input type="radio" name="${endpoint}" value="${option}"> ${option}</label><br>`;
                            });

                            $(targetElement).html(optionsHtml);

                            $('#nextBtn').off().on('click', function() {
                            let selectedCategory = $("input[name='categories']:checked").val();
                            let selectedDescription = $("input[name='propositions']:checked").val();

                            console.log("Selectionner catégorie: " + selectedCategory + " Correct catégorie: " + correctCategory);
                            console.log("Selectionner description: " + selectedDescription + " Correct description: " + correctDescription);
                            
                            let imageScore = 0; 

                            if (selectedCategory === correctCategory && selectedDescription === correctDescription) {
                                imageScore = 1;
                            } else if (selectedCategory === correctCategory || selectedDescription === correctDescription) {
                                imageScore = 0.5; 
                            }

                            score += imageScore; 
                            console.log("Score actuel: " + score);

                            let questionData = {
                                image: images[currentIndex],
                                category: correctCategory,
                                description: correctDescription,
                                selectedCategory: selectedCategory,
                                selectedDescription: selectedDescription,
                                score: imageScore
                            };
                            
                            let questionResults = JSON.parse(localStorage.getItem('questionResults')) || [];
                            questionResults.push(questionData);
                            localStorage.setItem('questionResults', JSON.stringify(questionResults));

                            currentIndex++;
                            if (currentIndex < images.length) {
                                loadQuestion(images[currentIndex]);
                            } else {
                                window.location.href = 'result.php';
                            }
                        });

                        },
                        error: function(xhr, status, error) {
                            console.error('Erreur lors du chargement des options:', error);
                        }
                    });
                }

                function shuffle(array) {
                    let currentIndex = array.length, randomIndex;
                    while (currentIndex !== 0) {
                        randomIndex = Math.floor(Math.random() * currentIndex);
                        currentIndex--;
                        [array[currentIndex], array[randomIndex]] = [
                            array[randomIndex], array[currentIndex]
                        ];
                    }
                    return array;
                }
            });
    </script>
</body>
</html>
