import { Link, useParams } from "react-router-dom";
import { useOrderDetail } from "../../hooks/orders/useOrders";

export default function OrderDetailPage() {
    const params = useParams();
    const orderId = Number(params.orderId);

    const isValidId = Number.isFinite(orderId) && orderId > 0;

    const { data: order, loading, error } =
        useOrderDetail(isValidId ? orderId : null);

    if (!isValidId) {
        return (
            <div className="alert alert-warning mt-4">
                ID commande invalide.
            </div>
        );
    }

    return (
        <section className="w-100">

            <nav aria-label="breadcrumb" className="mb-3">
                <ol className="breadcrumb">
                    <li className="breadcrumb-item"><Link to="/">Accueil</Link></li>
                    <li className="breadcrumb-item"><Link to="/orders">Commandes</Link></li>
                    <li className="breadcrumb-item active">
                        {order ? `Commande #${order.id}` : "Chargement..."}
                    </li>
                </ol>
            </nav>

            <Link to="/orders" className="btn btn-outline-secondary btn-sm mb-4">
                Retour aux commandes
            </Link>

            {loading ? (
                <div className="text-center py-5">
                    <div className="spinner-border text-primary" />
                </div>
            ) : null}

            {error ? (
                <div className="alert alert-danger">{error}</div>
            ) : null}

            {order ? (
                <div className="card shadow-sm border-0 p-4">

                    <h1 className="fw-bold mb-3">
                        Commande #{order.id}
                    </h1>

                    <p className="mb-2">
                        Référence : {order.reference || "N/A"}
                    </p>

                    <p className="mb-2">
                        Client ID : {order.customerId ?? "N/A"}
                    </p>

                    <h3 className="text-primary fw-bold mb-3">
                        Total : {order.totalPaid} €
                    </h3>

                    <span className="badge bg-primary mb-3">
                        Statut : {order.status}
                    </span>

                    <hr />

                    <p className="text-muted small">
                        Paiement : {order.payment || "N/A"}
                    </p>

                    <p className="text-muted small">
                        Livraison : {order.shippingNumber || "N/A"}
                    </p>

                    <p className="text-muted small">
                        Date : {order.dateAdd}
                    </p>

                    <span className={`badge ${order.valid ? "bg-success" : "bg-danger"}`}>
                        {order.valid ? "Valide" : "Non valide"}
                    </span>

                </div>
            ) : null}
        </section>
    );
}