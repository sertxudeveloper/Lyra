import SimpleCard from "./components/Cards/SimpleCard";
import MetricCard from "./components/Cards/MetricCard";
import PartitionCard from "./components/Cards/PartitionCard";

export default function (app) {
  app.component('card-simple', SimpleCard)
  app.component('card-metric', MetricCard)
  app.component('card-partition', PartitionCard)
}
