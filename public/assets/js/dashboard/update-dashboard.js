import { displayErrors } from "../utils/forms.js";
import { ToastUtils } from "../utils/toast.js";
import {
  fetchOngoingTrips,
  fetchTotalHoursTraveledData,
  fetchTripDurationsData,
  fetchTripsDataForLastSevenDays,
  fetchVehicleUsageData,
} from "./get-dashboard.js";
import { DashboardModule } from "./init-dashboard.js";

export function saveEndTrip() {
  const tripId = DashboardModule.getSelectedOnGoingTripId();

  if (!tripId) return;

  const csrfToken = document.querySelector("#endTripForm input[name='csrfToken']").value;
  const endDate = document.getElementById("endDate").value;
  const endTime = document.getElementById("endTime").value;
  const endLocation = document.getElementById("endLocation").value;
  const endKm = document.getElementById("endKm").value;
  const purpose = document.getElementById("purpose").value;

  fetch("index.php?page=endTrip", {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ csrfToken, tripId, endDate, endTime, endLocation, endKm, purpose, duration }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.errors || data.error) {
        displayErrors({ formId: "endTripForm", validationErrors: data.errors, error: data.error });
      } else if (data.success) {
        const endTripModal = document.getElementById("endTripModal");
        const modalInstance = bootstrap.Modal.getInstance(endTripModal);
        modalInstance.hide();
        fetchOngoingTrips();
        fetchTripsDataForLastSevenDays();
        fetchTripDurationsData();
        fetchVehicleUsageData();
        fetchTotalHoursTraveledData();
        ToastUtils.showSuccess("Trip has been eneded sucecssfully!");
      } else {
        console.error("Error:", data);
      }
    })
    .catch((error) => console.error("Error ending trip:", error));
}
