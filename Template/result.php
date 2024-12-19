<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultat</title>
    
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <!-- Titre -->
            <div class="col-12 text-center mb-4">
                <h2 id="viewName"></h2>
            </div>
        </div>

        <div class="row justify-content-center">
            <!-- Tableau des résultats -->
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white text-center">
                        Résultats
                    </div>
                    <table class="table table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Question</th>
                                <th>Image</th>
                                <th>Note</th>
                            </tr>
                        </thead>
                        <tbody id="resultTable">
                            <!-- Résultats dynamiques ici -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Résumé -->
            <div class="col-md-4">
                <div class="card shadow-sm text-center">
                    <div class="card-body">
                        <img id="imgResult" src="" alt="">
                        <h5 id="cateResult"><strong>Danger</strong></h5>
                        <p id="descResult">Descente dangereuse.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mt-4">
           <div class="col-md-6">
                <div class="col-md-4 text-center">
                    <button class="btn btn-primary btn-block" id="retryBtn">Ressayer</button>
                </div>
           </div>
        </div>
    </div>

    <script>
    let questionResults = JSON.parse(localStorage.getItem('questionResults')) || [];
    let totalScore = 0;
    let resultTable = document.getElementById('resultTable');

    let imgResult = document.getElementById('imgResult');
    let cateResult = document.getElementById('cateResult');
    let descResult = document.getElementById('descResult');

    questionResults.forEach(function(result, index) {
        let score = result.score;
        totalScore += score;

        let row = document.createElement('tr');

        let questionCell = document.createElement('td');
        questionCell.textContent = index + 1;

        let imageCell = document.createElement('td');
        let imageElement = document.createElement('img');
        imageElement.src = `../data/${result.image}`;
        imageElement.alt = result.image;
        imageElement.style.width = '50px';
        imageElement.style.height = '50px';
        imageCell.appendChild(imageElement);

        let scoreCell = document.createElement('td');
        scoreCell.textContent = score;

        row.appendChild(questionCell);
        row.appendChild(imageCell);
        row.appendChild(scoreCell);

        row.addEventListener('mouseover', function () {
            imgResult.src = `../data/${result.image}`;
            imgResult.alt = result.image;
            cateResult.innerText = result.category || 'Catégorie non définie';
            descResult.innerText = result.description || 'Description non disponible';
        });

        resultTable.appendChild(row);
    });

    let totalScoreCell = document.createElement('tr');
    totalScoreCell.innerHTML = `<td><strong>Total</strong></td><td></td><td><strong>${totalScore}/10</strong></td>`;
    resultTable.appendChild(totalScoreCell);

    document.getElementById('retryBtn').addEventListener('click', function() {
        localStorage.removeItem('questionResults');
        window.location.href = 'start.php';
    });

    let name = localStorage.getItem('name');
    if (name) {
        document.getElementById('viewName').innerText = 'Bonjour, ' + name + '!';
    } else {
        document.getElementById('viewName').innerText = 'Nom non trouvé.';
    }
</script>

</body>
</html>
