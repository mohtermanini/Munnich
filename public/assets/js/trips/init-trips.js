import { initTripsEvents } from "./events-trips.js";
import { fetchTrips, fetchVehicles } from "./get-trips.js";

export const TripModule = (() => {
  let currentPage = 1;
  const limit = 10;
  let tripsData = [];
  let selectedTripId = null;

  const init = () => {
    fetchVehicles();
    fetchTrips(currentPage, limit);
    initTripsEvents();
  };

  const startApp = () => {
    if (document.readyState === "loading") {
      document.addEventListener("DOMContentLoaded", init);
    } else {
      init();
    }
  };

  startApp();

  return {
    getCurrentLimit: () => limit,
    getCurrentPage: () => currentPage,
    setCurrentPage: (page) => {
      currentPage = page;
    },
    getTripsData: () => tripsData,
    setTripsData: (data) => {
      tripsData = data;
    },
    getSelectedTripId: () => selectedTripId,
    setSelectedTripId: (tripId) => {
      selectedTripId = tripId;
    },
  };
})();
