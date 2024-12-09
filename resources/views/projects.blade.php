<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Projects List</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        <div id="card" class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#f8f4f3]">
            <div>
                <a href="/">
                    <h2 class="font-bold text-3xl">Projects <span
                            class="bg-[#f84525] text-white px-2 rounded-md">List</span>
                    </h2>
                </a>
            </div>
        </div>
    </div>
    <script>


        window.onload = function() {

            var channel = Echo.channel('projects_collection');
            channel.listen("ProjectsCollectionListener", function(data) {
                const card = document.getElementById("card")
                const projects = data.data

                for(const project in projects) {
                    card.innerHTML += `<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                        <h3><b>ID:</b> ${projects[project].id}</h3>
                        <h3><b>Name:</b> ${projects[project].name}</h3>
                    </div>`
                }
            })
        }
    </script>
</body>

</html>
