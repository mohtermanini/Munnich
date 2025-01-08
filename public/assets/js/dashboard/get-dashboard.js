import { generatePagination } from "../utils/pagination.js";
import { DashboardModule } from "./init-dashboard.js";
import { renderOngoingTrips } from "./ui-dashboard.js";

export function fetchTripsDataForLastSevenDays() {
  fetch("index.php?page=getTripsDataForLastSevenDays")
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((item) => item.label);
      const tripCounts = data.map((item) => item.value);

      const ctx = document.getElementById("tripsChart").getContext("2d");

      if (window.lastTripsChart && window.lastTripsChart.destroy) {
        window.lastTripsChart.destroy();
      }

      // Create new chart
      window.lastTripsChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "# of Trips",
              data: tripCounts,
              backgroundColor: "rgba(75, 192, 192, 0.2)",
              borderColor: "rgba(75, 192, 192, 1)",
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    })
    .catch((error) => {
      console.error("Error fetching trips data:", error);
    });
}

// Fetch dynamic data for Trip Duration (Max, Min, Avg)
export function fetchTripDurationsData() {
  fetch("index.php?page=getTripDurationsData")
    .then((response) => response.json())
    .then((data) => {
      const ctx = document.getElementById("durationChart").getContext("2d");
      if (window.durationChart && window.durationChart.destroy) {
        window.durationChart.destroy();
      }

      // Create new chart
      window.durationChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Max Duration", "Min Duration", "Avg Duration"],
          datasets: [
            {
              label: "Trip Duration (Minutes)",
              data: [data.max, data.min, data.avg],
              backgroundColor: ["#FF5733", "#28A745", "#FFEB3B"],
              borderColor: ["#FF5733", "#28A745", "#FFEB3B"],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          indexAxis: "y",
          scales: {
            x: {
              beginAtZero: true,
            },
          },
        },
      });
    })
    .catch((error) => {
      console.error("Error fetching trip duration data:", error);
    });
}

// Fetch data for Vehicle Usage
export function fetchVehicleUsageData() {
  fetch("index.php?page=getVehicleUsageData")
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((item) => item.vehicle_name);
      const usageData = data.map((item) => item.trip_count);

      const ctx = document.getElementById("vehicleUsageChart").getContext("2d");
      if (window.vehicleUsageChart && window.vehicleUsageChart.destroy) {
        window.vehicleUsageChart.destroy();
      }

      // Create new chart
      window.vehicleUsageChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Total Trips",
              data: usageData,
              backgroundColor: ["#FF6F61", "#5F6368", "#FFEB3B"],
              borderColor: ["#FF6F61", "#5F6368", "#FFEB3B"],
              borderWidth: 1,
              barThickness: 50,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
            },
          },
        },
      });
    })
    .catch((error) => {
      console.error("Error fetching vehicle usage data:", error);
    });
}

export function fetchOngoingTrips(page = 1, limit = 10) {
  const params = new URLSearchParams({
    currentPage: page,
    limit,
  });

  fetch(`index.php?page=getOngoingTrips&${params.toString()}`)
    .then((response) => response.json())
    .then((data) => {
      DashboardModule.setOnGoingTrips(data);
      renderOngoingTrips();
      generatePagination("trips-pagination-links", data.meta?.totalPages, page, (newPage) => {
        fetchOngoingTrips(newPage, limit);
      });
    })
    .catch((error) => {
      console.error("Error fetching ongoing trips data:", error);
    });
}

export function getOnGoingTripById(tripId) {
  return DashboardModule.getOnGoingTrips()?.data.find((t) => t.trip_id === parseInt(tripId));
}

export function fetchTotalHoursTraveledData() {
  fetch("index.php?page=getTotalHoursTraveled")
    .then((response) => response.json())
    .then((data) => {
      const labels = data.map((item) => item.label); // Example: ["Vehicle A", "Vehicle B", "Total"]
      const hoursData = data.map((item) => item.hours); // Example: [10, 15, 25]

      const ctx = document.getElementById("totalHoursChart").getContext("2d");
      if (window.totalHoursChart && window.totalHoursChart.destroy) {
        window.totalHoursChart.destroy();
      }

      // Create new chart
      window.totalHoursChart = new Chart(ctx, {
        type: "bar",
        data: {
          labels: labels,
          datasets: [
            {
              label: "Total Hours",
              data: hoursData,
              backgroundColor: labels.map((label) => (label === "Total" ? "rgba(255, 99, 132, 0.5)" : "rgba(54, 162, 235, 0.5)")), // Highlight total bar
              borderColor: labels.map((label) => (label === "Total" ? "rgba(255, 99, 132, 1)" : "rgba(54, 162, 235, 1)")),
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: "Hours",
              },
            },
            x: {
              title: {
                display: true,
                text: "Vehicles",
              },
            },
          },
          plugins: {
            legend: {
              display: true,
            },
            tooltip: {
              callbacks: {
                label: (context) => `${context.dataset.label}: ${context.raw} hours`,
              },
            },
          },
        },
      });
    })
    .catch((error) => {
      console.error("Error fetching total hours traveled data:", error);
    });
}
