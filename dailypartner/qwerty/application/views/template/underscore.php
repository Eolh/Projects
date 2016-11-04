<head>
    <title> Test of Underscore !!! </title>
    <meta charset="utf-8">

    <script src="http://localhost:8088/public/export_modules/jquery-1.12.3.min.js"></script>
    <script src="http://localhost:8088/public/export_modules/underscore-min.js"></script>

    <script>
        var scores = [84, 99, 91, 123, 203, 44, 39, 69, 1];

        topScores  = [];
        scoreLimit = 90;

        for(var i=0 ; i<=scores.length ; i++)
        {
            if(scores[i] > scoreLimit)
            {
                topScores.push(scores[i]);
            }
        }

        window.alert(topScores);

        topScores = [];

        topScores = _.select(scores, function(score) { return score > scoreLimit; });

        window.alert(topScores);
    </script>

    <script>

        var exampleOfUnderscore = _.noConflict(); /* underscore object를 참조가능 */

        function lyricSegment(n) {

            return exampleOfUnderscore.chain([])
                .push("<p>")/* 배열에 값을 넣는다. */
                .push(n + " bottles of beer on the wall")
                .push(n + " bottles of beer")
                .push("take one down, pass it around")
                .tap(function(lyrics) {
                    if(n>1) {
                        lyrics.push((n-1) + " bottles of beer on the wall.");
                    } else {
                        lyrics.push("no more bottles of beer on the wall.");
                    }
                })
                .push("</p>")
                .value();
        }

        function song(start, end, lyricGen) {
            return exampleOfUnderscore.reduce(exampleOfUnderscore.range(start, end, -1),
                function(acc, n) {
                    return acc.concat(lyricGen(n));
                }, []);
        }

        /*document.write(song(99, 0, lyricSegment));*/

        var nums = [1,2,3,4,5];

        function doubleAll(array) {
            return exampleOfUnderscore.map(array, function(n) {
                return n*2
            }); /* 기존에 존재하는 배열의 값을 매핑하여서 새로운 배열을 생성. mapping = 도표화 */
        }

        var test = doubleAll(nums);

        function average(array) {
            var sum = exampleOfUnderscore.reduce(array, function(a,b) {
                return a+b;
            });

            return sum / exampleOfUnderscore.size(array);
        }

        var scale = 7;

        var good = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15];

        var example = exampleOfUnderscore.select(good, function(good) {
            return good > scale;
        });

        document.write(example);

        function onlyEven(array) {
            return exampleOfUnderscore.filter(array, function(n) {
                return (n%2) == 0;
            });
            /* filter callback 함수에 지정된 조건을 충족하는 배열의 요소를 반환! */
            /* push   배열에 새 요소를 추가학 배열의 새 길이를 반환 */
            /* map    배열의 각 요소에 대해 정의된 callback 함수를 호출하여 결과가 포함되도록 배열을 반환 */
        }

        /*document.write(nums.length + "<br>");

         document.write(average(nums));*/

        //document.write(onlyEven(nums));

    </script>

</head>
<body>

</body>