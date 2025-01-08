import { initDashboardEvents } from "./events-dashboard.js";
import { fetchOngoingTrips, fetchTotalHoursTraveledData, fetchTripDurationsData, fetchTripsDataForLastSevenDays, fetchVehicleUsageData } from "./get-dashboard.js";

export const DashboardModule = (() => {
  let onGoingTrips = null;
  let selectedTripId = null;

  const init = () => {
    fetchTripsDataForLastSevenDays();
    fetchTripDurationsData();
    fetchVehicleUsageData();
    fetchOngoingTrips();
    fetchTotalHoursTraveledData();
    initDashboardEvents();
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
    getOnGoingTrips: () => onGoingTrips,
    setOnGoingTrips: (data) => {
      onGoingTrips = data;
    },
    getSelectedOnGoingTripId: () => selectedTripId,
    setSelectedOnGoingTripId: (tripId) => {
      selectedTripId = tripId;
    },
  };
})();
