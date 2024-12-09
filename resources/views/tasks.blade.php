<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tasks List</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        <div id="card" class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#f8f4f3]">
            <div>
                <a href="/">
                    <h2 class="font-bold text-3xl">Tasks <span
                            class="bg-[#f84525] text-white px-2 rounded-md">List</span>
                    </h2>
                </a>
            </div>
        </div>
    </div>
    <script>


        window.onload = function() {

            var channel = Echo.channel('tasks_collection');
            channel.listen("TaskCollection", function(data) {
                const card = document.getElementById("card")
                const tasks = data.data

                for(const task in tasks) {
                    subtask = tasks[task].subTask

                    card.innerHTML +=
                    `<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                        <h3><b>ID:</b> ${tasks[task].id}</h3>
                        <h3><b>Name:</b> ${tasks[task].name}</h3>
                        <h3><b>Status:</b> ${tasks[task].status}</h3>
                        <h3><b>Start Time:</b> ${tasks[task].startTime}</h3>
                        <h3><b>End Time:</b> ${tasks[task].endTime}</h3>
                        <h3><b>Name User:</b> ${tasks[task].usersName}</h3>
                        <h3><b>Project Name:</b> ${tasks[task].projectName}</h3>
                    </div>`
                }
            })
        }
    </script>
</body>

</html>
