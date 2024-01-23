<script>
import { defineComponent, ref, onMounted } from "vue";
import { Marker } from "vue3-google-map";
import $ from "jquery";
import { Head } from "@inertiajs/vue3";
import { router } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";

export default defineComponent({
    emits: ["position_changed", "tilt_changed"],
    components: { Marker, Head, Link },
    props: { auth: Object },
    setup() {
        const center = ref({ lat: 0, lng: 0 });
        const markers = ref([]);
        const klikmarker = ref([]);
        const selectedMarker = ref(true);
        const mapInstance = ref(null);
        const address = ref("");

        const getCurrentLocation = () => {
            if (markers.value.length > 0) {
                // Set the center to the first marker's position
                center.value = markers.value[0].position;
            } else {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const userLocation = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            center.value = userLocation;
                            // markers.value.push({
                            //   position: userLocation,
                            //   label: "",
                            //   title: "Your Location",
                            // });
                        },
                        (error) => {
                            console.error("Error getting location:", error);
                        }
                    );
                } else {
                    console.error(
                        "Geolocation is not supported by this browser."
                    );
                }
            }
        };

        const getReverseGeocoding = (lat, lng) => {
            return fetch(
                `https://maps.googleapis.com/maps/api/geocode/json?latlng=${lat},${lng}&key=AIzaSyD2dASx5Zo68GSyZuPjUs-4SBLYGsn4OPQ`
            )
                .then((response) => response.json())
                .then((data) => {
                    if (data.results && data.results.length > 0) {
                        const address = data.results[0].formatted_address;
                        return address; // Kembalikan alamat
                    } else {
                        return null; // Kembalikan null jika tidak ada hasil
                    }
                })
                .catch((error) => {
                    console.error(error);
                    return null; // Kembalikan null jika terjadi kesalahan
                });
        };

        const logout = () => {
            router.post("/logout");
        };

        const formInput = ref({
            // title: "",
            notes: "",
        });

        // const handleMapClick = (event) => {
        //     const clickedPosition = {
        //         lat: event.latLng.lat(),
        //         lng: event.latLng.lng(),
        //     };

        //     markers.value.push({
        //         position: clickedPosition,
        //         label: "",
        //         title: "New Marker",
        //         showForm: true,
        //     });

        //     center.value = clickedPosition;
        //     klikmarker.value = [];
        //     $("#showmarker").hide();
        // };

        const handleMapClick = (event) => {
            const clickedPosition = {
                lat: event.latLng.lat(),
                lng: event.latLng.lng(),
            };

            // Panggil fungsi reverse geocoding dengan latitude dan longitude
            getReverseGeocoding(clickedPosition.lat, clickedPosition.lng).then(
                (address) => {
                    // Anda bisa menggunakan alamat di sini
                    console.log(address);
                }
            );

            markers.value.push({
                position: clickedPosition,
                label: "",
                title: "New Marker",
                showForm: true,
            });

            center.value = clickedPosition;
            klikmarker.value = [];
            $("#showmarker").hide();
        };

        const mapWasMounted = (_map) => {
            mapInstance.value = _map;
        };

        const handleMarkerClick = (clickedMarker) => {
            const index = markers.value.findIndex(
                (marker) =>
                    marker.position.lat === clickedMarker.position.lat &&
                    marker.position.lng === clickedMarker.position.lng
            );

            // Check if the clicked marker has an ID
            if (!markers.value[index].id) {
                // Marker does not have an ID, remove it directly
                markers.value.splice(index, 1);
            } else {
                selectedMarker.value = {
                    id: markers.value[index].id,
                    notes: clickedMarker.notes,
                    name: clickedMarker.name,
                    date: clickedMarker.date,
                    showForm: true,
                };
                $("#showmarker").show();
            }

            // zoom.value = 16;
            center.value = clickedMarker.position;

            // Manually update the map
            if (mapInstance.value) {
                mapInstance.value.setZoom(zoom.value);
                mapInstance.value.setCenter(center.value);
            }

            // Call getReverseGeocoding with the clicked marker's position
            getReverseGeocoding(
                clickedMarker.position.lat,
                clickedMarker.position.lng
            )
                .then((addr) => {
                    if (addr) {
                        address.value = addr; // Update the address variable with the received address
                    }
                })
                .catch((error) => console.error(error));
        };

        const fetchData = async () => {
            try {
                const response = await fetch("/api/maps");
                const data = await response.json();

                markers.value = data.map((map) => ({
                    position: {
                        lat: parseFloat(map.lat),
                        lng: parseFloat(map.lng),
                    },
                    id: map.id,
                    label: "",
                    notes: map.notes,
                    name: map.name,
                    date: map.date,
                }));
            } catch (error) {
                console.error("Error fetching data:", error);
            }
        };

        const saveFormData = () => {
            // lat: markers.value[markers.value.length - 1].position.lat,
            // lng: markers.value[markers.value.length - 1].position.lng,
            if (markers.value.length > 0) {
                const lastMarker = markers.value[markers.value.length - 1];

                if (
                    lastMarker.position &&
                    lastMarker.position.lat &&
                    lastMarker.position.lng
                ) {
                    const formData = {
                        notes: formInput.value.notes,
                        lat: lastMarker.position.lat,
                        lng: lastMarker.position.lng,
                    };

                    // Menggunakan Ajax jQuery untuk mengirim data
                    $.ajax({
                        url: "/api/maps",
                        type: "POST",
                        contentType: "application/json",
                        data: JSON.stringify(formData),
                        success: function (data) {
                            alert("Data saved : Success", data);

                            markers.value[
                                markers.value.length - 1
                            ].showForm = false;
                            formInput.value = {
                                notes: "",
                            };
                            fetchData();
                        },
                        error: function (error) {
                            console.error("Error saving data:", error);
                        },
                    });
                } else {
                    console.error("Error: Marker position data is incomplete");
                }
            } else {
                console.error("Error: No markers available to save");
            }
        };

        const editSaveFormData = () => {
            var no = $("#notes").val();
            if (selectedMarker.value && selectedMarker.value.id) {
                const formData = {
                    notes: no,
                };
                console.log("formInput:", formInput.value); // Log formInput to the console

                $.ajax({
                    url: `/api/maps/edit/${selectedMarker.value.id}`,
                    type: "POST",
                    contentType: "application/json",
                    data: JSON.stringify(formData),
                    success: function (data) {
                        alert("Data saved : Success", data);
                        // Update the notes of the selectedMarker directly
                        selectedMarker.value.notes = formInput.notes;
                        $("#showmarker").hide();
                        fetchData();
                    },
                    error: function (error) {
                        console.error("Error saving data:", error);
                    },
                });
            } else {
                console.error("Error: No marker selected for editing");
            }
        };

        const deleteSaveFormData = () => {
            if (selectedMarker.value && selectedMarker.value.id) {
                $.ajax({
                    url: `/api/maps/delete/${selectedMarker.value.id}`,
                    type: "DELETE",
                    success: function (data) {
                        alert("Data deleted : Success", data);
                        const index = markers.value.findIndex(
                            (marker) => marker.id === selectedMarker.value.id
                        );
                        markers.value.splice(index, 1);
                        $("#showmarker").hide();
                        fetchData();
                    },
                    error: function (error) {
                        console.error("Error deleting data:", error);
                    },
                });
            } else {
                console.error("Error: No marker selected for deletion");
            }
        };

        const zoom = ref(7); // Atur level zoom awal

        const setPlace = (place) => {
            // Handle place changed event
            const selectedPosition = {
                lat: place.geometry.location.lat(),
                lng: place.geometry.location.lng(),
            };

            markers.value.push({
                position: selectedPosition,
                label: "",
                title: "New Marker",
                // showForm: true,
            });
            // console.log("Place set:", place);
            center.value = selectedPosition;
            zoom.value = 16; // Atur level zoom yang diinginkan
            // You can do something with the place data if needed
        };

        const closeShowMarker = () => {
            if (markers.value.length > 0) {
                const lastMarker = markers.value[markers.value.length - 1];

                if (lastMarker.id) {
                    // If the marker has an id, only hide the form
                    lastMarker.showForm = false;
                } else {
                    // If the marker doesn't have an id, remove it from the array
                    markers.value.pop();

                    // Set showForm to false for the new last marker
                    if (markers.value.length > 0) {
                        markers.value[
                            markers.value.length - 1
                        ].showForm = false;
                    }
                }
            }

            $("#showmarker").hide();
        };

        const closeModal = () => {
            const dialog = document.getElementById("logout_button");
            if (dialog) {
                dialog.close();
            }
        };

        onMounted(async () => {
            fetchData();
            getCurrentLocation();
            getReverseGeocoding();
            // handleMarkerClick();
            // $("#showmarker").hide();
        });

        return {
            zoom,
            center,
            logout,
            markers,
            address,
            setPlace,
            formInput,
            closeModal,
            klikmarker,

            handleMapClick,
            handleMarkerClick,
            deleteSaveFormData,
            editSaveFormData,
            closeShowMarker,

            selectedMarker,
            mapWasMounted,
            saveFormData,
        };
    },
});
</script>

<template>
    <Head title="Maps" />
    <div class="mx-auto relative">
        <div class="absolute top-2 left-48 z-10">
            <!-- Open the modal using ID.showModal() method -->
            <button
                class="btn bg-white border-none hover:bg-slate-200 text-base"
                onclick="my_modal_2.showModal()"
            >
                Information
            </button>
            <dialog id="my_modal_2" class="modal">
                <div class="modal-box bg-white">
                    <h1 class="text-xl font-semibold pb-2">Lokasi Anda :</h1>
                    <p>{{ center.lat }} Latitude, {{ center.lng }} Longitude</p>
                    <br />
                    <p>
                        Login Sebagai :
                        {{ auth.user.name }}
                    </p>
                    <p>
                        Login Sebagai :
                        {{ auth.user.email }}
                    </p>
                    <div class="flex justify-center gap-4">
                        <Link href="/dashboard">
                            <button
                                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-8 rounded z-40 mt-8"
                            >
                                Dashboard
                            </button>
                        </Link>
                        <button
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-8 rounded z-40 mt-8"
                            onclick="logout_button.showModal()"
                        >
                            Logout
                        </button>
                    </div>
                    <dialog id="logout_button" class="modal">
                        <div
                            class="modal-box bg-white flex gap-4 flex-col justify-center mx-auto"
                        >
                            <h1 class="text-xl font-semibold">
                                Kamu yakin ingin Log Out?
                            </h1>
                            <div class="flex gap-4 justify-center mt-8">
                                <button
                                    @click="logout"
                                    :href="route('logout')"
                                    method="post"
                                    as="button"
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded z-40"
                                >
                                    Ya
                                </button>
                                <button
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded z-40 modal-backdrop"
                                    @click="closeModal"
                                >
                                    Tidak
                                </button>
                            </div>
                        </div>
                    </dialog>
                </div>
                <form method="dialog" class="modal-backdrop">
                    <button>close</button>
                </form>
            </dialog>
        </div>

        <GMapMap
            api-key="AIzaSyD2dASx5Zo68GSyZuPjUs-4SBLYGsn4OPQ"
            id="google-map"
            style="width: 100%; height: 100vh"
            :center="center"
            :zoom="zoom"
            @load="mapWasMounted"
            @click="handleMapClick"
        >
            <GMapMarker
                :clickable="true"
                v-for="marker in markers"
                :key="marker.position.lat + marker.position.lng"
                :options="marker"
                @click="() => handleMarkerClick(marker)"
            />
            <!-- Menampilkan semua marker dalam array markers -->
            <div class="absolute right-16 top-[9px]">
                <GMapAutocomplete
                    placeholder="Search"
                    @place_changed="setPlace"
                    class="px-4 py-2 w-[512px] border rounded-md focus:outline-none focus:ring focus:border-blue-300 shadow-xl border-none"
                >
                </GMapAutocomplete>
            </div>
            <div class="absolute right-8 top-0"></div>
            <div
                v-if="markers.length && markers[markers.length - 1].showForm"
                class="absolute z-10 inset-1/2 transform translate-x-8 -translate-y-40"
            >
                <!-- Tambahkan kelas bg-white pada div untuk memberikan latar belakang putih -->
                <div class="bg-white w-72 h-auto rounded-md p-8 relative">
                    <form @submit.prevent="saveFormData">
                        <label for="notes">Description:</label>
                        <h1>{{ address }}</h1>
                        <textarea
                            v-model="formInput.notes"
                            id="notes"
                            class="w-full mb-2 p-2 border focus:outline-none focus:ring focus:border-blue-300"
                        ></textarea>

                        <div class="flex gap-4 justify-center">
                            <button
                                type="submit"
                                class="bg-blue-500 text-white py-2 px-4 rounded-md"
                            >
                                Save
                            </button>
                        </div>
                    </form>
                    <div class="absolute top-0 right-1">
                        <button
                            @click="closeShowMarker"
                            class="absolute top-2 right-2 text-red-500 hover:text-red-700 cursor-pointer"
                        >
                            X
                        </button>
                    </div>
                </div>
            </div>
            <div
                v-if="selectedMarker"
                id="showmarker"
                :data-id="selectedMarker ? selectedMarker.id : ''"
                class="absolute z-10 inset-1/2 transform translate-x-8 -translate-y-40"
                style="display: none"
            >
                <div
                    class="bg-white w-96 h-auto rounded-md p-8 relative shadow-xl"
                >
                    <form @submit.prevent="editSaveFormData">
                        <h1 class="pb-4">Alamat : {{ address }}</h1>

                        <label for="notes">Description:</label>
                        <!-- Textarea for 'user' role, disabled -->
                        <textarea
                            v-if="auth.user.role === 'user'"
                            id="notes"
                            class="w-full mb-2 p-2 border"
                            disabled
                        >
                            {{ selectedMarker ? selectedMarker.notes : "" }}
                        </textarea>

                        <!-- Textarea for other roles, enabled -->
                        <textarea
                            v-else
                            id="notes"
                            class="w-full mb-2 p-2 border"
                        >
                            {{ selectedMarker ? selectedMarker.notes : "" }}
                        </textarea>

                        <div class="flex gap-4 justify-center">
                            <button
                                v-if="
                                    auth.user.role === 'superuser' ||
                                    auth.user.role === 'admin' ||
                                    auth.user.role === 'superadmin'
                                "
                                type="submit"
                                class="bg-blue-500 text-white py-2 px-4 rounded-md"
                            >
                                Save
                            </button>
                            <button
                                v-if="
                                    auth.user.role === 'admin' ||
                                    auth.user.role === 'superadmin'
                                "
                                @click="deleteSaveFormData"
                                type="button"
                                class="bg-red-500 text-white py-2 px-4 rounded-md"
                            >
                                Delete
                            </button>
                        </div>
                        <div>
                            <h1>Dibuat oleh : {{ selectedMarker.name }}</h1>
                            <span>Dibuat pada : {{ selectedMarker.date }}</span>
                        </div>
                    </form>
                    <div class="absolute top-0 right-1">
                        <button
                            @click="closeShowMarker"
                            class="absolute top-2 right-2 text-red-500 hover:text-red-700 cursor-pointer"
                        >
                            X
                        </button>
                    </div>
                </div>
            </div>
        </GMapMap>
    </div>
</template>
