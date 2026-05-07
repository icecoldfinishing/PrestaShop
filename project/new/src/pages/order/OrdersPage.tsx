import { useState } from "react";
import { Link } from "react-router-dom";
import { useOrders } from "../../hooks/orders/useOrders";

export default function OrdersPage() {
    const [page, setPage] = useState(1);
    const limit = 12;

    const { data: orders, loading, error } = useOrders(page, limit);

    return (
        <section className="w-100">
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 className="fw-bold mb-1">Commandes</h1>
                    <p className="text-secondary mb-0">
                        Liste des commandes depuis l’API PrestaShop.
                    </p>
                </div>
            </div>

            {loading ? (
                <div className="text-center py-5">
                    <div className="spinner-border text-primary" role="status">
                        <span className="visually-hidden">Chargement...</span>
                    </div>
                </div>
            ) : null}

            {error ? (
                <div className="alert alert-danger" role="alert">
                    {error}
                </div>
            ) : null}

            {!loading && !error && (
                <>
                    <div className="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 mb-5">
                        {orders.map((order) => (
                            <div className="col" key={order.id}>
                                <div className="card h-100 shadow-sm border-0 rounded-3">
                                    <div className="card-body d-flex flex-column">

                                        <div className="text-muted small mb-1">
                                            Ref: {order.reference || "N/A"}
                                        </div>

                                        <h5 className="card-title">
                                            Commande #{order.id}
                                        </h5>

                                        <p className="text-muted mb-2">
                                            Total: {order.totalPaid} €
                                        </p>

                                        <p className="text-muted small mb-3">
                                            Date: {order.dateAdd}
                                        </p>

                                        <div className="mt-auto d-flex justify-content-between align-items-center">
                                            <span className="badge bg-primary">
                                                Statut: {order.status}
                                            </span>

                                            <Link
                                                to={`/orders/${order.id}`}
                                                className="btn btn-primary btn-sm"
                                            >
                                                Détail
                                            </Link>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>

                    <div className="d-flex justify-content-center">
                        <nav>
                            <ul className="pagination">
                                <li className={`page-item ${page === 1 ? "disabled" : ""}`}>
                                    <button
                                        className="page-link"
                                        onClick={() => setPage((p) => Math.max(1, p - 1))}
                                    >
                                        Précédent
                                    </button>
                                </li>

                                <li className="page-item active">
                                    <span className="page-link">{page}</span>
                                </li>

                                <li className={`page-item ${orders.length < limit ? "disabled" : ""}`}>
                                    <button
                                        className="page-link"
                                        onClick={() => setPage((p) => p + 1)}
                                    >
                                        Suivant
                                    </button>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </>
            )}
        </section>
    );
}