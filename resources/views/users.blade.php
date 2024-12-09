<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Users List</title>
    @vite(['resources/js/app.js', 'resources/css/app.css'])
</head>

<body>
    <div class="font-sans text-gray-900 antialiased">
        <div id="card" class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-[#f8f4f3]">
            <div>
                <a href="/">
                    <h2 class="font-bold text-3xl">Users <span
                            class="bg-[#f84525] text-white px-2 rounded-md">List</span>
                    </h2>
                </a>
            </div>
        </div>
    </div>
    <script>
        window.onload = function() {
            var channel = Echo.channel('users_collections');
            channel.listen("UsersCollectionListener", function(data) {
                const card = document.getElementById("card")
                const users = data.data
                for(const user in users) {
                    card.innerHTML +=
                    `<div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                        <h3><b>ID:</b> ${users[user].id}</h3>
                        <h3><b>Username:</b> ${users[user].username}</h3>
                        <h3><b>Name:</b> ${users[user].name}</h3>
                        <h3><b>Gender:</b> ${users[user].gender}</h3>
                        <h3><b>Role:</b> ${users[user].role}</h3>
                    </div>`
                }
            })
        }
    </script>
</body>

</html>
