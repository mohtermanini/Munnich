import { ToastUtils } from "../utils/toast.js";
import { fetchTrips } from "./get-trips.js";
import { TripModule } from "./init-trips.js";

export function deleteTrip() {
  const tripId = TripModule.getSelectedTripId();

  if (!tripId) return;

  fetch("index.php?page=deleteTrip", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      tripId: tripId,
    }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (data.success) {
        if (TripModule.getTripsData().length === 1) {
          TripModule.setCurrentPage(Math.max(TripModule.getCurrentPage() - 1, 1));
        }
        
        fetchTrips(TripModule.getCurrentPage(), TripModule.getCurrentLimit());

        ToastUtils.showSuccess("Trip deleted successfully.");
        const deleteTripModal = document.getElementById("deleteTripModal");
        const modalInstance = bootstrap.Modal.getInstance(deleteTripModal);
        modalInstance.hide();
      } else {
        ToastUtils.showError(data.error);
      }
    })
    .catch((error) => {
      console.error("Error deleting trip:", error);
    });
}
