import { useState } from "react";
import { Link } from "react-router-dom";
import { useCustomers } from "../../hooks/customers/useCustomers";

export default function CustomersPage() {
    const [page, setPage] = useState(1);
    const limit = 12;

    const { data: customers, loading, error } = useCustomers(page, limit);

    return (
        <section className="w-100">
            <div className="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 className="fw-bold mb-1">Liste des Clients</h1>
                    <p className="text-secondary mb-0">
                        Gestion des clients depuis l’API PrestaShop.
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
                        {customers.map((customer) => (
                            <div className="col" key={customer.id}>
                                <div className="card h-100 shadow-sm border-0 rounded-3">
                                    <div className="card-body d-flex flex-column">
                                        <div className="text-muted small mb-1">
                                            ID: {customer.id}
                                        </div>

                                        <h5 className="card-title">
                                            {customer.firstName} {customer.lastName}
                                        </h5>

                                        <p className="text-muted mb-2">
                                            {customer.email}
                                        </p>

                                        <div className="mt-auto d-flex justify-content-between align-items-center">
                                            <span
                                                className={`badge ${customer.active ? "bg-success" : "bg-secondary"
                                                    }`}
                                            >
                                                {customer.active ? "Actif" : "Inactif"}
                                            </span>

                                            <Link
                                                to={`/customers/${customer.id}`}
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
                        <nav aria-label="Page navigation">
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

                                <li className={`page-item ${customers.length < limit ? "disabled" : ""}`}>
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