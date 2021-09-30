import SimpleCard from "./components/cards/SimpleCard";
import MetricCard from "./components/cards/MetricCard";
import PartitionCard from "./components/cards/PartitionCard";

export default function (app) {
  app.component('card-simple', SimpleCard)
  app.component('card-metric', MetricCard)
  app.component('card-partition', PartitionCard)
}
